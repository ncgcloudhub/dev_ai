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
        Schema::create('a_i_chats', function (Blueprint $table) {
            $table->id();
            $table->string('expert_name');
            $table->string('character_name');
            $table->string('slug');
            $table->text('description');
            $table->string('role');
            $table->text('expertise');
            $table->text('train_expert')->nullable();
            $table->text('image')->nullable();;
            $table->string('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('a_i_chats');
    }
};
