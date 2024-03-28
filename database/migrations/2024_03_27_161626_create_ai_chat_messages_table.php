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
        Schema::create('ai_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ai_chat_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('prompt')->nullable();
            $table->longText('response')->nullable();
            $table->longText('result')->nullable();
            $table->double('words')->default(0.00);
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('ai_chat_messages');
    }
};
