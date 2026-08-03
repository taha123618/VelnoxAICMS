<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Page\Enums\PageType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $blueprint->foreignUlid('category_id')
                ->nullable()
                ->constrained('categories');
            $blueprint->boolean('is_frontpage')
                ->default(false);
            $blueprint->string('type')->default(PageType::Page->value);
            $blueprint->foreignUlid('layout_id')->nullable();
            $blueprint->string('slug')->unique()->index();
            $blueprint->string('title');
            $blueprint->string('description')->nullable();
            $blueprint->json('content')->nullable();
            $blueprint->text('excerpt')->nullable();
            $blueprint->string('featured_image')->nullable();
            $blueprint->json('data')->nullable();
            $blueprint->integer('published_version_id')->nullable();
            $blueprint->datetime('published_at')->nullable();
            $blueprint->string('created_by')->nullable();
            $blueprint->string('updated_by')->nullable();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
