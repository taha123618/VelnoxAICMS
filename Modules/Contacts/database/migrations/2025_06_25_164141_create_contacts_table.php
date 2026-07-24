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
        Schema::create('contacts', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->string('name');
            $blueprint->string('subject');
            $blueprint->string('email');
            $blueprint->longText('body');
            $blueprint->boolean('subscribe_to_mail')->default(true);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
