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
        Schema::create('expirations', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // SSL, Hosting, API, etc.
            $table->string('name')->nullable(); // optional name like "GoDaddy SSL"
            $table->date('expires_on');
            $table->string('notes')->nullable(); // any extra details
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expirations');
    }
};
