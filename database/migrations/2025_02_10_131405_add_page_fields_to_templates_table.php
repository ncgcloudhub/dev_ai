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
        Schema::table('education_tools', function (Blueprint $table) {
            $table->string('page_title')->nullable()->after('prompt');
            $table->text('page_description')->nullable()->after('page_title');
            $table->text('page_tagging')->nullable()->after('page_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_tools', function (Blueprint $table) {
            $table->dropColumn(['page_title', 'page_description', 'page_tagging']);
        });
    }
};
