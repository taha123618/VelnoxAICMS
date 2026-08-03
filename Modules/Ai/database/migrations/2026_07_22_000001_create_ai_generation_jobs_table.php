<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_generation_jobs', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->foreignUlid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $blueprint->string('type')->default('section');
            $blueprint->text('prompt');
            $blueprint->string('status')->default('queued');
            $blueprint->unsignedInteger('progress')->default(0);
            $blueprint->json('result')->nullable();
            $blueprint->text('error')->nullable();
            $blueprint->timestamps();

            $blueprint->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_generation_jobs');
    }
};
