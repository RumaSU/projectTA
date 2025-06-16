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
        if (!Schema::hasTable('initial')) {
            Schema::create('initial', function (Blueprint $table) {
                $table->uuid('id_initial')->primary();
                $table->uuid('id_user');
                $table->string('title_initial')->nullable();
                // $table->string('file_name_initial');
                $table->string('unique_key')->unique();
                $table->boolean('default')->default(0);
                // $table->string('pin');
                $table->timestamps();
                
                $table->index('id_initial');
                $table->index('id_user');
            });
        }
        
        if (!Schema::hasTable('initial_disk')) { 
            Schema::create('initial_disk', function (Blueprint $table) {
                $table->uuid('id_initial_disk')->primary();
                $table->uuid('id_initial');
                $table->string('disk');
                $table->string('path');
                $table->string('file_name')->unique();
                // $table->string('pin');
                $table->timestamps();
                
                $table->index('id_initial');
            });
        }
        
        if (!Schema::hasTable('initial_document')) {
            Schema::create('initial_document', function (Blueprint $table) {
                $table->uuid('id_initial_document')->primary();
                $table->uuid('id_user');
                $table->uuid('id_initial');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('initial');
        Schema::dropIfExists('initial_document');
        Schema::dropIfExists('initial_disk');
    }
};
