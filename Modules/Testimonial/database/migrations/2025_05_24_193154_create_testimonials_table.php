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
        Schema::create('testimonials', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->string('name');
            $blueprint->string('avatar')->nullable();
            $blueprint->string('title')->nullable();
            $blueprint->text('comment');
            $blueprint->datetime('published_at')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
