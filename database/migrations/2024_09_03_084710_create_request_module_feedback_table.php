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
        Schema::create('request_module_feedback', function (Blueprint $table) {
            $table->id();
            $table->string('module')->nullable();
            $table->text('text')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->text('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_module_feedback');
    }
};
