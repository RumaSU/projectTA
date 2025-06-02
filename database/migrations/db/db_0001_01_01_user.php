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
        if (!Schema::hasTable('user')) {
            Schema::create('user', function (Blueprint $table) {
                $table->uuid('id_user')->primary();
                $table->string('email')->unique();
                $table->string('username')->unique();
                $table->string('password');
                $table->boolean('confirm_account')->default(false)->nullable();
                $table->rememberToken();
                $table->timestamps();
                
                $table->index('id_user');
            });
        }
        
        if (!Schema::hasTable('user_personal')) {
            Schema::create('user_personal', function (Blueprint $table) {
                $table->uuid('id_user');
                $table->string('fullname');
                $table->string('phone_number');
                $table->boolean('confirm_no_hp')->default(false)->nullable();
                $table->timestamps();
                
                $table->index('id_user');
            });
        }
        
        if (!Schema::hasTable('user_data')) {
            Schema::create('user_data', function (Blueprint $table) {
                $table->uuid('id_user');
                $table->string('nik')->unique();
                $table->timestamps();
                
                $table->index('id_user');
            });
        }
        
        if (!Schema::hasTable('user_media')) {
            Schema::create('user_media', function (Blueprint $table) {
                $table->uuid('id_user_media')->primary();
                $table->uuid('id_user');
                $table->string('media_type'); // photo_profile, etc ....
                $table->uuid('id_file_disk');
                // $table->string('disk');
                // $table->string('path');
                // $table->string('file_name')->unique();
                $table->timestamps();
                 
                $table->index('id_user');
                $table->index('id_file_disk');
            });
        }
        
        if (!Schema::hasTable('user_social')) {
            Schema::create('user_social', function (Blueprint $table) {
                $table->uuid('id_user');
                $table->string('platform'); // e.g., 'Twitter', 'LinkedIn'
                $table->string('url');
                $table->string('bio')->nullable();
                $table->timestamps();
                
                $table->index('id_user');
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
