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
        Schema::create('button_designs', function (Blueprint $table) {
            $table->id();
            $table->string('button_type'); // e.g., save, edit, delete, cancel
            $table->string('icon'); // e.g., fa-save, fa-edit
            $table->text('classes'); // JSON or text to store CSS classes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('button_designs');
    }
};
