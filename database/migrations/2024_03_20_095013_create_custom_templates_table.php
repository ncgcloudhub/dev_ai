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
        Schema::create('custom_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name');
            $table->string('slug');
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->text('description')->nullable();
            $table->text('input_types');
            $table->text('input_names');
            $table->text('input_labels');
            $table->bigInteger('total_word_generated');
            $table->text('prompt')->nullable();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('custom_template_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_templates');
    }
};
