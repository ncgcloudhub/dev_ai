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
        Schema::create('generated_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image');
            $table->text('prompt');
            $table->string('model'); // 'dalle', 'sd', etc.
            $table->string('resolution')->nullable(); 
            $table->string('festival')->nullable();   
            $table->string('style')->nullable();      
            $table->boolean('in_frontend')->default(false); 
            $table->string('seed')->nullable();       
            $table->unsignedBigInteger('downloads')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_images');
    }
};
