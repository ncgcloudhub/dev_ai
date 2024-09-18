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
        Schema::create('education_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grade_classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('age')->nullable();;
            $table->text('difficulty_level')->nullable();;
            $table->text('tone')->nullable();;
            $table->text('persona')->nullable();;
            $table->text('topic');
            $table->text('additional_details')->nullable();;
            $table->text('example')->nullable();;
            $table->text('reference')->nullable();;
            $table->text('prompt');
            $table->text('generated_content');
            $table->text('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_contents');
    }
};
