<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        // 1) Attempts AFTER UPDATE -> create/update results + notifications
        if (Schema::hasTable('attempts')) {
            if ($driver === 'mysql') {
                DB::unprepared(<<<'SQL'
                DROP TRIGGER IF EXISTS trg_attempts_after_update;
                CREATE TRIGGER trg_attempts_after_update
                AFTER UPDATE ON attempts FOR EACH ROW
                BEGIN
                    DECLARE v_total INT DEFAULT 0;
                    DECLARE v_pass_pct INT DEFAULT 60;
                    DECLARE v_teacher_id INT DEFAULT NULL;

                    IF ((NEW.completed_at IS NOT NULL AND OLD.completed_at IS NULL)
                        OR (NEW.score IS NOT NULL AND OLD.score IS NOT NULL AND NEW.score <> OLD.score)) THEN

                        SELECT COALESCE(SUM(points_awarded),0) INTO v_total FROM attempt_answers WHERE attempt_id = NEW.id;
                        SELECT COALESCE(q.pass_percentage, (SELECT value FROM settings WHERE `key`='default_pass_percentage' LIMIT 1)) INTO v_pass_pct FROM quizzes q WHERE q.id = NEW.quiz_id;
                        SELECT q.created_by INTO v_teacher_id FROM quizzes q WHERE q.id = NEW.quiz_id LIMIT 1;

                        IF EXISTS(SELECT 1 FROM results WHERE attempt_id = NEW.id) THEN
                            UPDATE results
                            SET score = v_total, passed = (v_total >= v_pass_pct), completed_at = NEW.completed_at, user_id = NEW.user_id, quiz_id = NEW.quiz_id
                            WHERE attempt_id = NEW.id;
                        ELSE
                            INSERT INTO results (attempt_id, user_id, quiz_id, score, passed, created_at, started_at, completed_at)
                            VALUES (NEW.id, NEW.user_id, NEW.quiz_id, v_total, (v_total >= v_pass_pct), NOW(), NEW.started_at, NEW.completed_at);
                        END IF;

                        -- student notification
                        INSERT INTO notifications (id, type, notifiable_type, notifiable_id, data, created_at, updated_at)
                        VALUES (UUID(), 'App\\Notifications\\QuizCompletedNotification', 'App\\Models\\User', NEW.user_id, JSON_OBJECT('attempt_id', NEW.id, 'score', v_total), NOW(), NOW());

                        -- teacher notification (if teacher exists)
                        IF v_teacher_id IS NOT NULL THEN
                            INSERT INTO notifications (id, type, notifiable_type, notifiable_id, data, created_at, updated_at)
                            VALUES (UUID(), 'App\\Notifications\\QuizCompletedTeacherNotification', 'App\\Models\\User', v_teacher_id, JSON_OBJECT('attempt_id', NEW.id, 'student_id', NEW.user_id, 'score', v_total), NOW(), NOW());
                        END IF;
                    END IF;
                END;
                SQL
                );
            } elseif ($driver === 'pgsql') {
                DB::unprepared(<<<'SQL'
                CREATE OR REPLACE FUNCTION trg_attempts_after_update_fn() RETURNS trigger AS $$
                DECLARE
                    v_total INTEGER := 0;
                    v_pass_pct INTEGER := 60;
                    v_teacher_id INTEGER;
                BEGIN
                    IF ((NEW.completed_at IS NOT NULL AND OLD.completed_at IS NULL)
                        OR (NEW.score IS NOT NULL AND OLD.score IS NOT NULL AND NEW.score IS DISTINCT FROM OLD.score)) THEN

                        SELECT COALESCE(SUM(points_awarded),0) INTO v_total FROM attempt_answers WHERE attempt_id = NEW.id;
                        SELECT COALESCE(q.pass_percentage, (SELECT value::int FROM settings WHERE key='default_pass_percentage' LIMIT 1)) INTO v_pass_pct FROM quizzes q WHERE q.id = NEW.quiz_id;
                        SELECT q.created_by INTO v_teacher_id FROM quizzes q WHERE q.id = NEW.quiz_id LIMIT 1;

                        IF EXISTS(SELECT 1 FROM results WHERE attempt_id = NEW.id) THEN
                            UPDATE results SET score = v_total, passed = (v_total >= v_pass_pct), completed_at = NEW.completed_at, user_id = NEW.user_id, quiz_id = NEW.quiz_id WHERE attempt_id = NEW.id;
                        ELSE
                            INSERT INTO results (attempt_id, user_id, quiz_id, score, passed, created_at, started_at, completed_at)
                            VALUES (NEW.id, NEW.user_id, NEW.quiz_id, v_total, (v_total >= v_pass_pct), now(), NEW.started_at, NEW.completed_at);
                        END IF;

                        INSERT INTO notifications (id, type, notifiable_type, notifiable_id, data, created_at, updated_at)
                        VALUES (gen_random_uuid(), 'App\\Notifications\\QuizCompletedNotification', 'App\\Models\\User', NEW.user_id, to_jsonb(json_build_object('attempt_id', NEW.id, 'score', v_total))::text, now(), now());

                        IF v_teacher_id IS NOT NULL THEN
                            INSERT INTO notifications (id, type, notifiable_type, notifiable_id, data, created_at, updated_at)
                            VALUES (gen_random_uuid(), 'App\\Notifications\\QuizCompletedTeacherNotification', 'App\\Models\\User', v_teacher_id, to_jsonb(json_build_object('attempt_id', NEW.id, 'student_id', NEW.user_id, 'score', v_total))::text, now(), now());
                        END IF;
                    END IF;
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                DROP TRIGGER IF EXISTS trg_attempts_after_update ON attempts;
                CREATE TRIGGER trg_attempts_after_update
                AFTER UPDATE ON attempts
                FOR EACH ROW EXECUTE PROCEDURE trg_attempts_after_update_fn();
                SQL
                );
            }
        }

        // 2) Questions AFTER INSERT/DELETE -> maintain quizzes.total_questions
        if (Schema::hasTable('questions') && Schema::hasTable('quizzes') && DB::getSchemaBuilder()->hasColumn('quizzes', 'total_questions')) {
            if ($driver === 'mysql') {
                DB::unprepared(<<<'SQL'
                DROP TRIGGER IF EXISTS trg_questions_after_insert;
                CREATE TRIGGER trg_questions_after_insert AFTER INSERT ON questions FOR EACH ROW
                BEGIN
                    UPDATE quizzes SET total_questions = COALESCE(total_questions,0) + 1 WHERE id = NEW.quiz_id;
                END;

                DROP TRIGGER IF EXISTS trg_questions_after_delete;
                CREATE TRIGGER trg_questions_after_delete AFTER DELETE ON questions FOR EACH ROW
                BEGIN
                    UPDATE quizzes SET total_questions = GREATEST(COALESCE(total_questions,0) - 1, 0) WHERE id = OLD.quiz_id;
                END;
                SQL
                );
            } elseif ($driver === 'pgsql') {
                DB::unprepared(<<<'SQL'
                CREATE OR REPLACE FUNCTION trg_questions_after_ins_fn() RETURNS trigger AS $$
                BEGIN
                    UPDATE quizzes SET total_questions = COALESCE(total_questions,0) + 1 WHERE id = NEW.quiz_id;
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                CREATE OR REPLACE FUNCTION trg_questions_after_del_fn() RETURNS trigger AS $$
                BEGIN
                    UPDATE quizzes SET total_questions = GREATEST(COALESCE(total_questions,0) - 1, 0) WHERE id = OLD.quiz_id;
                    RETURN OLD;
                END;
                $$ LANGUAGE plpgsql;

                DROP TRIGGER IF EXISTS trg_questions_after_insert ON questions;
                CREATE TRIGGER trg_questions_after_insert AFTER INSERT ON questions FOR EACH ROW EXECUTE PROCEDURE trg_questions_after_ins_fn();

                DROP TRIGGER IF EXISTS trg_questions_after_delete ON questions;
                CREATE TRIGGER trg_questions_after_delete AFTER DELETE ON questions FOR EACH ROW EXECUTE PROCEDURE trg_questions_after_del_fn();
                SQL
                );
            }
        }

        // 3) attempt_answers BEFORE INSERT/UPDATE -> block changes if attempt completed
        if (Schema::hasTable('attempt_answers') && Schema::hasTable('attempts')) {
            if ($driver === 'mysql') {
                DB::unprepared(<<<'SQL'
                DROP TRIGGER IF EXISTS trg_attempt_answers_before_ins;
                CREATE TRIGGER trg_attempt_answers_before_ins BEFORE INSERT ON attempt_answers FOR EACH ROW
                BEGIN
                    IF (SELECT completed_at FROM attempts WHERE id = NEW.attempt_id) IS NOT NULL THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Attempt already completed - cannot add/modify answers.';
                    END IF;
                END;

                DROP TRIGGER IF EXISTS trg_attempt_answers_before_upd;
                CREATE TRIGGER trg_attempt_answers_before_upd BEFORE UPDATE ON attempt_answers FOR EACH ROW
                BEGIN
                    IF (SELECT completed_at FROM attempts WHERE id = NEW.attempt_id) IS NOT NULL THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Attempt already completed - cannot add/modify answers.';
                    END IF;
                END;
                SQL
                );
            } elseif ($driver === 'pgsql') {
                DB::unprepared(<<<'SQL'
                CREATE OR REPLACE FUNCTION trg_attempt_answers_before_fn() RETURNS trigger AS $$
                DECLARE v_completed timestamp;
                BEGIN
                    SELECT completed_at INTO v_completed FROM attempts WHERE id = NEW.attempt_id;
                    IF v_completed IS NOT NULL THEN
                        RAISE EXCEPTION 'Attempt already completed - cannot add/modify answers.';
                    END IF;
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                DROP TRIGGER IF EXISTS trg_attempt_answers_before_ins ON attempt_answers;
                CREATE TRIGGER trg_attempt_answers_before_ins BEFORE INSERT ON attempt_answers FOR EACH ROW EXECUTE PROCEDURE trg_attempt_answers_before_fn();

                DROP TRIGGER IF EXISTS trg_attempt_answers_before_upd ON attempt_answers;
                CREATE TRIGGER trg_attempt_answers_before_upd BEFORE UPDATE ON attempt_answers FOR EACH ROW EXECUTE PROCEDURE trg_attempt_answers_before_fn();
                SQL
                );
            }
        }

        // 4) sessions/users AFTER INSERT -> create login_histories entries
        // Prefer sessions table if present and has user_id, otherwise skip (app already logs via controllers normally)
        if (Schema::hasTable('sessions') && DB::getSchemaBuilder()->hasColumn('sessions', 'user_id') && Schema::hasTable('login_histories')) {
            if ($driver === 'mysql') {
                DB::unprepared(<<<'SQL'
                DROP TRIGGER IF EXISTS trg_sessions_after_insert;
                CREATE TRIGGER trg_sessions_after_insert AFTER INSERT ON sessions FOR EACH ROW
                BEGIN
                    INSERT INTO login_histories (user_id, ip_address, user_agent, login_at, created_at, updated_at)
                    VALUES (NEW.user_id, NEW.ip_address, NEW.user_agent, NOW(), NOW(), NOW());
                END;
                SQL
                );
            } elseif ($driver === 'pgsql') {
                DB::unprepared(<<<'SQL'
                CREATE OR REPLACE FUNCTION trg_sessions_after_ins_fn() RETURNS trigger AS $$
                BEGIN
                    INSERT INTO login_histories (user_id, ip_address, user_agent, login_at, created_at, updated_at)
                    VALUES (NEW.user_id, NEW.ip_address, NEW.user_agent, now(), now(), now());
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;

                DROP TRIGGER IF EXISTS trg_sessions_after_insert ON sessions;
                CREATE TRIGGER trg_sessions_after_insert AFTER INSERT ON sessions FOR EACH ROW EXECUTE PROCEDURE trg_sessions_after_ins_fn();
                SQL
                );
            }
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        // Attempts triggers
        if ($driver === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS trg_attempts_after_update;');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_questions_after_insert;');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_questions_after_delete;');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_attempt_answers_before_ins;');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_attempt_answers_before_upd;');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_sessions_after_insert;');
        } elseif ($driver === 'pgsql') {
            DB::unprepared('DROP TRIGGER IF EXISTS trg_attempts_after_update ON attempts;');
            DB::unprepared('DROP FUNCTION IF EXISTS trg_attempts_after_update_fn();');

            DB::unprepared('DROP TRIGGER IF EXISTS trg_questions_after_insert ON questions;');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_questions_after_delete ON questions;');
            DB::unprepared('DROP FUNCTION IF EXISTS trg_questions_after_ins_fn();');
            DB::unprepared('DROP FUNCTION IF EXISTS trg_questions_after_del_fn();');

            DB::unprepared('DROP TRIGGER IF EXISTS trg_attempt_answers_before_ins ON attempt_answers;');
            DB::unprepared('DROP TRIGGER IF EXISTS trg_attempt_answers_before_upd ON attempt_answers;');
            DB::unprepared('DROP FUNCTION IF EXISTS trg_attempt_answers_before_fn();');

            DB::unprepared('DROP TRIGGER IF EXISTS trg_sessions_after_insert ON sessions;');
            DB::unprepared('DROP FUNCTION IF EXISTS trg_sessions_after_ins_fn();');
        }
    }
};
