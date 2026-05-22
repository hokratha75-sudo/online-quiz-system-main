<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('class_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('class_model_id')
                ->constrained('class_models')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['user_id', 'class_model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_user');
    }
};