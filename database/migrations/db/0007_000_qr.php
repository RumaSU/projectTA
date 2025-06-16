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
        if (!Schema::hasTable('file_disk')) {
            Schema::create('file_disk', function (Blueprint $table) {
                $table->uuid('id_file_disk')->primary();
                // $table->string('key_file')->unique();
                $table->string('disk');
                $table->string('path');
                $table->string('file_name')->unique();
                $table->string('extension');
                $table->string('mime_type')->nullable();
                $table->timestamps();
                
                $table->index('id_file_disk');
                // $table->index('key_file');
                $table->index('file_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
