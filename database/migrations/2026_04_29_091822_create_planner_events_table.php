<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('planner_events', function (Blueprint $row) {
            $row->id();
            $row->foreignId('user_id')->constrained()->onDelete('cascade');
            $row->string('title');
            $row->dateTime('start');
            $row->dateTime('end')->nullable();
            $row->string('background_color')->nullable();
            $row->string('border_color')->nullable();
            $row->boolean('is_all_day')->default(false);
            $row->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('planner_events');
    }
};
