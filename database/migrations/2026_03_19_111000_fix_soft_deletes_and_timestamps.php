<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('departments')) {
            $hasUpdatedAt = Schema::hasColumn('departments', 'updated_at');
            $hasDeletedAt = Schema::hasColumn('departments', 'deleted_at');

            if (! $hasUpdatedAt || ! $hasDeletedAt) {
                Schema::table('departments', function (Blueprint $table) use ($hasUpdatedAt, $hasDeletedAt) {
                    if (! $hasUpdatedAt) {
                        $table->timestamp('updated_at')->nullable();
                    }

                    if (! $hasDeletedAt) {
                        $table->softDeletes();
                    }
                });
            }
        }

        if (Schema::hasTable('subjects')) {
            $hasDeletedAt = Schema::hasColumn('subjects', 'deleted_at');

            if (! $hasDeletedAt) {
                Schema::table('subjects', function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('subjects') && Schema::hasColumn('subjects', 'deleted_at')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasTable('departments')) {
            $hasUpdatedAt = Schema::hasColumn('departments', 'updated_at');
            $hasDeletedAt = Schema::hasColumn('departments', 'deleted_at');

            if ($hasUpdatedAt || $hasDeletedAt) {
                Schema::table('departments', function (Blueprint $table) use ($hasUpdatedAt, $hasDeletedAt) {
                    if ($hasDeletedAt) {
                        $table->dropSoftDeletes();
                    }

                    if ($hasUpdatedAt) {
                        $table->dropColumn('updated_at');
                    }
                });
            }
        }
    }
};
