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
        Schema::create('fields', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->foreignUlid('collection_id')->constrained('collections')->cascadeOnDelete();
            $blueprint->string('name');
            $blueprint->string('handle');
            $blueprint->string('type');
            $blueprint->jsonb('validation_rules')->nullable();
            $blueprint->jsonb('ui_config')->nullable();
            $blueprint->integer('order_column')->default(0);
            $blueprint->timestamps();

            $blueprint->unique(['collection_id', 'handle']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
