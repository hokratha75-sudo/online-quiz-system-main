<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('answer_id')->nullable()->constrained()->nullOnDelete();
            $table->text('short_text')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->integer('points_awarded')->default(0);
            $table->text('teacher_feedback')->nullable();
            $table->timestamps();
        });

        Schema::table('results', function (Blueprint $table) {
            $table->boolean('is_published')->default(true)->after('passed');
            $table->text('teacher_feedback')->nullable()->after('is_published');
            $table->decimal('manual_score', 5, 2)->nullable()->after('score');
        });
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn(['is_published', 'teacher_feedback', 'manual_score']);
        });
        Schema::dropIfExists('attempt_answers');
    }
};
