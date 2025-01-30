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
        Schema::table('dynamic_pages', function (Blueprint $table) {
            $table->string('thumbnail_image')->nullable()->after('content');
            $table->string('banner_image')->nullable()->after('thumbnail_image');
            $table->string('attached_files')->nullable()->after('banner_image'); // JSON format to store multiple files
            $table->enum('status', ['draft', 'published'])->default('draft')->after('attached_files');
            $table->string('tags')->nullable()->after('status'); // JSON for multiple tags
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade')->after('tags'); // Assuming users table exists
            $table->enum('page_status', ['inprogress', 'completed'])->default('inprogress')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_pages', function (Blueprint $table) {
            $table->dropColumn([
                'thumbnail_image', 'banner_image', 'attached_files', 'status', 'tags', 'created_by','page_status'
            ]);
        });
    }
};
