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
        if (!Schema::hasTable('files_disk')) {
            Schema::create('files_disk', function (Blueprint $table) {
                $table->uuid('id_file_disk')->primary();
                $table->string('disk');
                $table->string('path');
                $table->string('file_name')->unique();
                $table->string('extension');
                $table->string('mime_type')->nullable();
                $table->unsignedInteger('size_byte');
                
                $table->char('hash_file', 172);
                $table->char('hash_row', 172);
                $table->string('hash_type', 25);
                
                $table->boolean('status'); // status file found
                $table->timestamp('last_check')->useCurrent();
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('files_disk_entity')) {
            Schema::create('files_disk_entity', function (Blueprint $table) {
                $table->uuid('id_file_disk_entity')->primary();
                $table->uuid('id_file_disk')->index();
                $table->uuid('owner_id')->index();
                $table->string('entity_type')->index();
                $table->uuid('id_entity')->index();
                
                $table->string('file_client_name')->index();
                
                $table->char('hash_row', 172);
                $table->string('hash_type', 25);
                
                
                $table->timestamps();
            });
        }
        
        if (! Schema::hasTable('files_disk_token')) {
            Schema::create('files_disk_token', function (Blueprint $table) {
                $table->uuid('id_file_disk_token')->primary();
                $table->uuid('id_file_disk_entity')->index();
                $table->uuid('shared_user_id')->nullable()->index();
                $table->string('token')->unique();
                
                $table->char('hash_row', 172);
                $table->string('hash_type', 25);
                
                $table->boolean('is_expired')->default(false);
                $table->timestamp('expired_at')->nullable();
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
