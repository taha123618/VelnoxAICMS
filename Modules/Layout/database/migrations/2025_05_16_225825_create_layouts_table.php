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
        Schema::create('layouts', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->string('name');
            $blueprint->json('content')->nullable();
            $blueprint->integer('published_version_id')->nullable();
            $blueprint->datetime('published_at')->nullable();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layouts');
    }
};
