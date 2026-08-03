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
        Schema::create('seo_metas', function (Blueprint $blueprint): void {
            $blueprint->id();
            $blueprint->morphs('seoable');
            $blueprint->string('title')->nullable();
            $blueprint->text('description')->nullable();
            $blueprint->string('keywords')->nullable();
            $blueprint->string('canonical_url')->nullable();
            $blueprint->string('og_image')->nullable();
            $blueprint->string('robots')->default('index, follow');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_metas');
    }
};
