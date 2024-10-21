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
        Schema::create('education_tools', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      
            $table->string('icon')->nullable();  
            $table->string('slug')->unique();         
            $table->text('description')->nullable();    
            $table->json('input_types')->nullable();    
            $table->json('input_names')->nullable();   
            $table->json('input_labels')->nullable();   
            $table->json('input_placeholders')->nullable(); 
            $table->text('prompt')->nullable();         
            $table->text('popular')->nullable();         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_tools');
    }
};
