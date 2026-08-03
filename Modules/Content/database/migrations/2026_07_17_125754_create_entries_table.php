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
        Schema::create('entries', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->foreignUlid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $blueprint->foreignUlid('collection_id')->constrained('collections')->cascadeOnDelete();
            $blueprint->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $blueprint->jsonb('data');
            $blueprint->string('status')->default('draft');
            $blueprint->timestamp('published_at')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
