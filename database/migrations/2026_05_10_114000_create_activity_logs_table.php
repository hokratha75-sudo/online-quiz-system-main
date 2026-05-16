<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $blueprint->string('action'); // e.g., 'login', 'create_quiz', 'delete_user'
            $blueprint->string('model_type')->nullable(); // e.g., 'App\Models\Quiz'
            $blueprint->unsignedBigInteger('model_id')->nullable();
            $blueprint->json('details')->nullable(); // Changes or additional info
            $blueprint->string('ip_address', 45)->nullable();
            $blueprint->string('user_agent')->nullable();
            $blueprint->timestamps();

            $blueprint->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
