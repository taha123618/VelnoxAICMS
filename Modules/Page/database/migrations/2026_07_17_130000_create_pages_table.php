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
        Schema::create('pages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('category_id')
                ->nullable()
                ->constrained('categories');
            $table->boolean('is_frontpage')
                ->default(false);
            $table->string('type')->default(PageType::Page->value);
            $table->foreignUlid('layout_id')->nullable();
            $table->string('slug')->unique()->index();
            $table->string('title');
            $table->string('description')->nullable();
            $table->json('content')->nullable();
            $table->text('excerpt')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('data')->nullable();
            $table->integer('published_version_id')->nullable();
            $table->datetime('published_at')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
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
