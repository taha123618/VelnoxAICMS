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
        Schema::create('menu_items', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->foreignUlid('menu_id')->constrained();
            $blueprint->string('parent_id')->nullable();
            $blueprint->string('type');
            $blueprint->string('label');
            $blueprint->string('path')->nullable(); // page id or post id or external url
            $blueprint->string('target')->nullable();
            $blueprint->integer('sort_order')->default(0);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
