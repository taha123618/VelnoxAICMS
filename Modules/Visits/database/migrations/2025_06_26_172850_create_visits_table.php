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
        Schema::create('visits', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->nullableUlidMorphs('visitable');
            $table->nullableUlidMorphs('visitor');
            $table->string('method')->nullable();
            $table->longText('request')->nullable();
            $table->longText('url')->nullable();
            $table->longText('referer')->nullable();
            $table->text('languages')->nullable();
            $table->text('useragent')->nullable();
            $table->text('headers')->nullable();
            $table->string('device')->nullable();
            $table->string('platform')->nullable();
            $table->string('browser')->nullable();
            $table->ipAddress('request_ip')->nullable();
            $table->ipAddress('location_ip')->nullable();
            $table->string('country_name')->nullable();
            $table->string('country_code')->nullable();
            $table->decimal('latitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->string('region_name')->nullable();
            $table->string('region_code')->nullable();
            $table->string('city_name')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('timezone')->nullable();
            $table->timestamps();
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
