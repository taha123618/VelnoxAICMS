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
        Schema::create('collections', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $blueprint->string('name');
            $blueprint->string('slug');
            $blueprint->text('description')->nullable();
            $blueprint->boolean('is_publishable')->default(true);
            $blueprint->timestamps();

            $blueprint->unique(['tenant_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
