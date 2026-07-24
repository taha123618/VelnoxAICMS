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
        Schema::create('published_pages', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->foreignUlid('page_id')->constrained('pages')->cascadeOnDelete();
            $blueprint->string('title');
            $blueprint->string('description')->nullable();
            $blueprint->json('content')->nullable();
            $blueprint->text('excerpt')->nullable();
            $blueprint->string('featured_image')->nullable();
            $blueprint->json('data')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('published_pages');
    }
};
