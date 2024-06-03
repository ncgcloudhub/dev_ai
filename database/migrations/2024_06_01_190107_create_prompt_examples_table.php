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
        Schema::create('prompt_examples', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prompt_id');
            $table->text('example'); 
             $table->boolean('active')->default(true);
            $table->timestamps();
            $table->foreign('prompt_id')->references('id')->on('prompt_libraries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prompt_examples');
    }
};
