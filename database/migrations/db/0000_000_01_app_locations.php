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
        
        Schema::create('app_locations_regions', function (Blueprint $table) {
            $table->unsignedMediumInteger('id', true)->primary();
            $table->string('name', 100);
            $table->text('translations');
            $table->tinyInteger('flag')->default(1)->nullable(false);
            $table->string('wikiDataId')->comment('Rapid API GeoDB Cities')->nullable();
            $table->timestamps();
            
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        
        Schema::create('app_locations_subregions', function (Blueprint $table) {
            $table->unsignedMediumInteger('id', true)->primary();
            $table->string('name', 100);
            $table->text('translations');
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId')->comment('Rapid API GeoDB Cities')->nullable();
            
            $table->unsignedMediumInteger('region_id');
            $table->timestamps();
            
            $table->index('region_id', 'subregion_continent');
            $table->foreign('region_id', 'subregion_continent_final')->references('id')
                ->on('app_locations_regions')->restrictOnDelete();
            
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        
        Schema::create('app_locations_countries', function (Blueprint $table) {
            $table->unsignedMediumInteger('id', true)->primary();
            $table->string('name', 100);
            
            $table->char('iso3', 3)->nullable();
            $table->char('numeric_code', length: 3)->nullable();
            
            $table->char('iso2', 2)->nullable();
            
            $table->string('phonecode')->nullable();
            $table->string('capital')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_name')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('tld')->nullable();
            $table->string('native')->nullable();
            
            $table->string('region')->nullable();
            $table->unsignedMediumInteger('region_id')->nullable();
            $table->string('subregion')->nullable();
            $table->unsignedMediumInteger('subregion_id')->nullable();
            
            $table->string('nationality')->nullable();
            
            $table->text('timezones');
            $table->text('translations');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            
            $table->string('emoji', 191)->nullable();
            $table->string('emojiU', 191)->nullable();
            
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId')->comment('Rapid API GeoDB Cities')->nullable();
            
            $table->timestamps();
            
            $table->index('region_id', 'country_continent');
            $table->index('subregion_id', 'country_subregion');
            
            $table->foreign('region_id', 'country_continent_final')->references('id')
                ->on('app_locations_regions')->restrictOnDelete();
            $table->foreign('subregion_id', 'country_subregion_final')->references('id')
                ->on('app_locations_subregions')->restrictOnDelete();
            
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        
        Schema::create('app_locations_states', function (Blueprint $table) {
            
            $table->unsignedMediumInteger('id', true)->primary();
            $table->string('name');
            
            $table->unsignedMediumInteger('country_id');
            $table->char('country_code', 2);
            
            $table->string('fips_code');
            $table->char('iso2', 2)->nullable();
            $table->string('type', 191)->nullable();
            
            $table->integer('level')->nullable();
            $table->integer('parent_id')->nullable();
            
            $table->string('native', 191)->nullable();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId')->comment('Rapid API GeoDB Cities')->nullable();
            
            $table->timestamps();
            
            $table->index('country_id', 'country_region');
            
            $table->foreign('country_id', 'country_region_final')->references('id')
                ->on('app_locations_countries')->restrictOnDelete();
            
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        
        
        Schema::create('app_locations_cities', function (Blueprint $table) {
            
            $table->unsignedMediumInteger('id', true)->primary();
            $table->string('name');
            
            $table->unsignedMediumInteger('state_id');
            $table->string('state_code');
            
            $table->unsignedMediumInteger('country_id');
            $table->char('country_code', 2);
            
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId')->comment('Rapid API GeoDB Cities')->nullable();
            
            $table->timestamps();
            
            $table->index('state_id', 'cities_test_ibfk_1');
            $table->index('country_id', 'cities_test_ibfk_2');
            
            $table->foreign('state_id', 'cities_ibfk_1')->references('id')
                ->on('app_locations_states')->restrictOnDelete();
            $table->foreign('country_id', 'cities_ibfk_2')->references('id')
                ->on('app_locations_countries')->restrictOnDelete();
            
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ...
    }
};
