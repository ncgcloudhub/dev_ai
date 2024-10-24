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
        Schema::create('education_tools_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('tools_id')->constrained('education_tools')->onDelete('cascade');
            $table->unique(['user_id', 'tools_id'], 'unique_favorite');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('education_tools_favorites');
    }
};
