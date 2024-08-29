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
        Schema::table('prompt_libraries', function (Blueprint $table) {
            $table->string('page_title')->nullable();
            $table->text('page_description')->nullable();
            $table->text('page_tagging')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prompt_libraries', function (Blueprint $table) {
            $table->dropColumn(['page_title', 'page_description', 'page_tagging']);
        });
    }
};
