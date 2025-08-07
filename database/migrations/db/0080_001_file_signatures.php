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
        if (! Schema::hasTable('files_signatures')) {
            Schema::create('files_signatures', function (Blueprint $table) {
                $table->uuid('id_file_signature')->primary();
                $table->uuid('id_file_disk')->unique();
                $table->uuid('id_user')->index();
                
                $table->enum('type', ['signature', 'paraf']);
                $table->string('disk');
                $table->string('path');
                $table->string('file_name')->unique();
                $table->string('file_client_name')->nullable();
                $table->string('extension');
                $table->string('mime_type')->nullable();
                $table->unsignedInteger('size_byte');
                
                $table->char('hash_row', 172);
                $table->string('hash_type', 25);
                
                $table->timestamps();
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
