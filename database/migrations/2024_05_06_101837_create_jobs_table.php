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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('slug');
            $table->string('job_position');
            $table->string('job_category')->nullable();
            $table->string('job_type')->nullable();
            $table->text('description')->nullable();
            $table->text('skills')->nullable();
            $table->text('responsibility')->nullable();
            $table->integer('no_of_vacancy')->nullable();
            $table->string('experience')->nullable();
            $table->date('last_date_of_apply')->nullable();
            $table->date('close_date')->nullable();
            $table->integer('start_salary')->nullable();
            $table->integer('last_salary')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('tags')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
