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
        Schema::create('visits', function (Blueprint $blueprint): void {
            $blueprint->ulid('id')->primary();
            $blueprint->nullableUlidMorphs('visitable');
            $blueprint->nullableUlidMorphs('visitor');
            $blueprint->string('method')->nullable();
            $blueprint->longText('request')->nullable();
            $blueprint->longText('url')->nullable();
            $blueprint->longText('referer')->nullable();
            $blueprint->text('languages')->nullable();
            $blueprint->text('useragent')->nullable();
            $blueprint->text('headers')->nullable();
            $blueprint->string('device')->nullable();
            $blueprint->string('platform')->nullable();
            $blueprint->string('browser')->nullable();
            $blueprint->ipAddress('request_ip')->nullable();
            $blueprint->ipAddress('location_ip')->nullable();
            $blueprint->string('country_name')->nullable();
            $blueprint->string('country_code')->nullable();
            $blueprint->decimal('latitude')->nullable();
            $blueprint->decimal('longitude')->nullable();
            $blueprint->string('region_name')->nullable();
            $blueprint->string('region_code')->nullable();
            $blueprint->string('city_name')->nullable();
            $blueprint->string('zip_code')->nullable();
            $blueprint->string('timezone')->nullable();
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
