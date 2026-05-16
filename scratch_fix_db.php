<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasTable('activity_logs')) {
        Schema::create('activity_logs', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $blueprint->string('action');
            $blueprint->string('model_type')->nullable();
            $blueprint->unsignedBigInteger('model_id')->nullable();
            $blueprint->json('details')->nullable();
            $blueprint->string('ip_address', 45)->nullable();
            $blueprint->string('user_agent')->nullable();
            $blueprint->timestamps();
            $blueprint->index(['model_type', 'model_id']);
        });
        echo "Table activity_logs created successfully.";
    } else {
        echo "Table activity_logs already exists.";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
