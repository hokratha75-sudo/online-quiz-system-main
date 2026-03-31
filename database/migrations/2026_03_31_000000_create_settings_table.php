<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general'); // general, quiz, security, smtp
            $table->timestamps();
        });

        // Seed default settings
        $defaults = [
            // General
            ['key' => 'site_name', 'value' => 'QuizMaster v2.0', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'admin@quizmaster.com', 'group' => 'general'],
            ['key' => 'timezone', 'value' => 'Asia/Phnom_Penh', 'group' => 'general'],
            ['key' => 'site_logo', 'value' => null, 'group' => 'general'],
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'general'],
            ['key' => 'institution_name', 'value' => 'My University', 'group' => 'general'],

            // Quiz Rules
            ['key' => 'default_time_limit', 'value' => '30', 'group' => 'quiz'],
            ['key' => 'default_pass_percentage', 'value' => '60', 'group' => 'quiz'],
            ['key' => 'max_attempts', 'value' => '1', 'group' => 'quiz'],
            ['key' => 'shuffle_questions', 'value' => '1', 'group' => 'quiz'],
            ['key' => 'shuffle_answers', 'value' => '1', 'group' => 'quiz'],
            ['key' => 'show_result_immediately', 'value' => '1', 'group' => 'quiz'],
            ['key' => 'allow_review', 'value' => '1', 'group' => 'quiz'],

            // Security
            ['key' => 'disable_right_click', 'value' => '1', 'group' => 'security'],
            ['key' => 'tab_switch_detection', 'value' => '1', 'group' => 'security'],
            ['key' => 'enforce_fullscreen', 'value' => '0', 'group' => 'security'],
            ['key' => 'max_violations', 'value' => '3', 'group' => 'security'],
            ['key' => 'auto_submit_on_violation', 'value' => '0', 'group' => 'security'],
        ];

        $now = now();
        foreach ($defaults as &$d) {
            $d['created_at'] = $now;
            $d['updated_at'] = $now;
        }

        \Illuminate\Support\Facades\DB::table('settings')->insert($defaults);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
