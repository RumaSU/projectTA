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
        
        if (!Schema::hasTable('file_disk_entity')) {
            Schema::create('file_disk_entity', function (Blueprint $table) {
                $table->uuid('id_file_disk_entity')->primary();
                $table->uuid('id_file_disk');
                $table->uuid('id_user');
                $table->string('entity_type'); // Jenis entitas (misalnya: 'document', 'user', 'project', dsb)
                $table->uuid('id_entity'); // ID dari entitas yang terkait (misalnya, ID dokumen, ID user)
                $table->string('file_client_name');
                $table->timestamps();
                
                $table->index('entity_type');
                $table->index('id_entity');
                $table->index('id_user');
                $table->index('id_file_disk');
                $table->index('file_client_name');
            });
        }
        
        if (!Schema::hasTable('file_disk_key')) {
            Schema::create('file_disk_key', function (Blueprint $table) {
                $table->uuid('id_file_disk_key')->primary();
                $table->string('key_file')->unique();
                $table->string('entity_type'); // Jenis entitas (misalnya: 'document', 'user', 'project', dsb)
                $table->uuid('id_entity'); // ID dari entitas yang terkait (misalnya, ID dokumen, ID user)
                $table->uuid('id_user');
                $table->timestamps();
                
                $table->index('id_file_disk_key');
                $table->index('key_file');
                $table->index('id_user');
                $table->index('entity_type');
                $table->index('id_entity');
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
