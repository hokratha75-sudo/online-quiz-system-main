<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollment_histories', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('department_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('class_model_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained()->cascadeOnDelete();
            
            $table->string('action'); // 'enrolled', 'unenrolled', 'subject_assigned', 'subject_removed'
            $table->string('action_type'); // 'student', 'teacher', 'subject'
            $table->text('reason')->nullable();
            
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            
            $table->timestamps();
            
            $table->index('department_id');
            $table->index('user_id');
            $table->index('class_model_id');
            $table->index(['action', 'action_type']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_histories');
    }
};
