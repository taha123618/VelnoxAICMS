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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('menu_id')->constrained();
            $table->string('parent_id')->nullable();
            $table->string('type');
            $table->string('label');
            $table->string('path')->nullable(); // page id or post id or external url
            $table->string('target')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
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
