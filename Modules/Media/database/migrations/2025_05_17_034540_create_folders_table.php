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
        Schema::create('folders', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->foreignUlid('user_id')->nullable()->constrained('users');
            $blueprint->foreignUlid('parent_id')->nullable()->constrained('folders');
            $blueprint->string('name');
            $blueprint->integer('order_column')->default(0);
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};
