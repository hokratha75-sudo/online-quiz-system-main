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
        // 1. Create departments table
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Update majors table to link to departments
        Schema::table('majors', function (Blueprint $table) {
            $table->unsignedBigInteger('department_id')->nullable()->after('id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });

        // 3. Update class_models table
        Schema::table('class_models', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id');
            $table->string('academic_year')->nullable()->after('name');
        });

        // 4. Update subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('code')->nullable()->after('id');
            $table->integer('credits')->default(3)->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['code', 'credits']);
        });

        Schema::table('class_models', function (Blueprint $table) {
            $table->dropColumn(['code', 'academic_year']);
        });

        Schema::table('majors', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
        });

        Schema::dropIfExists('departments');
    }
};
