<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_models', function (Blueprint $table) {
            $table->id();

            // class name (Seeder uses this)
            $table->string('class_name');

            // relationship (Seeder uses major_id)
            $table->foreignId('major_id')
                ->nullable()
                ->constrained('majors')
                ->cascadeOnDelete();

            // enrollment flag
            $table->boolean('show_in_enrollments')->default(false);

            $table->timestamps();
        });

        // pivot table for users in class (teacher/student)
        Schema::create('class_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('class_model_id')
                ->constrained('class_models')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('role'); // teacher / student

            $table->timestamps();
        });

        // pivot table for class subjects
        Schema::create('class_subject', function (Blueprint $table) {
            $table->id();

            $table->foreignId('class_model_id')
                ->constrained('class_models')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->constrained('subjects')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_subject');
        Schema::dropIfExists('class_user');
        Schema::dropIfExists('class_models');
    }
};