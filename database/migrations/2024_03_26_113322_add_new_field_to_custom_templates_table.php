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
        Schema::table('custom_templates', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(); // Change 'string' to the appropriate data type
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_templates', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
