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
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('slug');
            $table->string('open_id_model')->nullable();
            $table->string('package_type')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('discounted_price', 10, 2)->nullable();            
            $table->integer('tokens')->nullable();
            $table->boolean('71_ai_templates')->nullable();
            $table->boolean('ai_chat')->nullable();
            $table->boolean('ai_code')->nullable();
            $table->boolean('text_to_speech')->nullable();
            $table->boolean('custom_templates')->nullable();
            $table->boolean('ai_blog_wizards')->nullable();
            $table->integer('images')->nullable();
            $table->boolean('ai_images')->nullable();
            $table->boolean('stable_diffusion')->nullable();
            $table->integer('speech_to_text')->nullable();
            $table->boolean('live_support')->nullable();
            $table->boolean('free_support')->nullable();
            $table->enum('active', ['active', 'inactive'])->nullable();
            $table->enum('popular', ['yes', 'no'])->nullable();            
            $table->text('additional_features')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};
