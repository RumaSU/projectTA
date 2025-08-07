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
        
        if (! Schema::hasTable('files_documents')) {
            Schema::create('files_documents', function (Blueprint $table) {
                $table->uuid('id_file_document')->primary();
                $table->uuid('id_file_disk')->unique();
                $table->uuid('owner_id')->index();
                
                $table->string('disk');
                $table->string('path');
                $table->string('file_name')->unique();
                $table->string('file_client_name');
                $table->string('extension');
                $table->string('mime_type')->nullable();
                $table->unsignedInteger('size_byte');
                
                $table->char('hash_row', 172);
                $table->string('hash_type', 25);
                
                $table->timestamps();
            });
        }
        
        
        // if (!Schema::hasTable('file_disk_view')) {
        //     Schema::create('file_disk_view', function (Blueprint $table) {
        //         $table->uuid('id_file_disk_view')->primary();
        //         $table->uuid('id_file_disk')->unique();
        //         $table->string('key_file')->unique();
        //         $table->timestamps();
                
        //         $table->index('id_file_disk');
        //         $table->index('key_file');
        //     });
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
