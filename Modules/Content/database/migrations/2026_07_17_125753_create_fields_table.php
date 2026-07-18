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
        Schema::create('fields', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('collection_id')->constrained('collections')->cascadeOnDelete();
            $table->string('name');
            $table->string('handle');
            $table->string('type');
            $table->jsonb('validation_rules')->nullable();
            $table->jsonb('ui_config')->nullable();
            $table->integer('order_column')->default(0);
            $table->timestamps();

            $table->unique(['collection_id', 'handle']);
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
