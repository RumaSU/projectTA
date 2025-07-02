<?php

use Illuminate\Support\Facades\DB; 

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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->uuid('id_user')->primary();
                $table->string('email')->unique();
                $table->string('username')->unique();
                $table->string('password');
                $table->timestamps();
                
                $table->index('id_user');
                $table->index('email');
                $table->index('username');
            });
        }
        
        if (!Schema::hasTable('users_personal')) { // data dasar user
            Schema::create('users_personal', function (Blueprint $table) {
                $table->uuid('id_user')->primary();
                // $table->string('first_name');
                // $table->string('last_name');
                $table->string('fullname');
                $table->enum('gender', ['Male', 'Female', 'Prefer not to say'])->default('Prefer not to say');
                $table->date('birthdate');
                $table->timestamps();
                
                $table->index('id_user');
            });
        }
        
        if (!Schema::hasTable('users_data')) { // data dasar user
            Schema::create('users_data', function (Blueprint $table) {
                $table->uuid('id_user')->primary();
                $table->string('job_regis_number');
                $table->string('job_type');
                $table->string('job_institute');
                // ...
                $table->timestamps();
                
                $table->index('id_user');
            });
        }
        
        if (!Schema::hasTable('users_phone')) { // data nomor telepon user
            Schema::create('users_phone', function (Blueprint $table) {
                $table->uuid('id_user_phone')->primary();
                $table->uuid('id_user');
                $table->string('phone_number');
                $table->enum('phone_type', ['Personal']); // 'Office', 'Home', 'Other'
                $table->timestamps();
                
                $table->index('id_user');
            });
        }
        
        // if (!Schema::hasTable('user_address')) { // data nomor telepon user
        //     Schema::create('user_address', function (Blueprint $table) {
        //         $table->uuid('id_user_address')->primary();
        //         $table->uuid('id_user');
        //         $table->string('phone_number');
        //         $table->enum('phone_type', ['Personal']); // 'Office', 'Home', 'Other'
        //         $table->timestamps();
                
        //         $table->index('id_user');
        //     });
        // }
        
        if (!Schema::hasTable('users_social_media')) { // data nomor telepon user
            Schema::create('users_social_media', function (Blueprint $table) {
                $table->uuid('id_user_social_media')->primary();
                $table->uuid('id_user');
                $table->string('social_name');
                $table->longText('social_link');
                $table->boolean('social_status')->default(true);
                $table->timestamps();
                
                $table->index('id_user');
            });
        }
        
        if (!Schema::hasTable('users_account_status')) { // seluruh status seperti email nomor telepon dan sejenisnya yang sudah terkonfirmasi
            Schema::create('users_account_status', function (Blueprint $table) {
                $table->uuid('id_user_account_status')->primary();
                $table->uuid('id_user');
                $table->enum('type', ['Email', 'Phone Number']);
                $table->boolean('is_confirmed')->default(false)->nullable();
                $table->timestamp('confirm_date')->nullable();
                $table->timestamps();
                
                $table->index('id_user');
            });
        }
        
        
        
        // if (!Schema::hasTable('user_personal')) {
        //     Schema::create('user_personal', function (Blueprint $table) {
        //         $table->uuid('id_user');
        //         $table->string('fullname');
        //         $table->string('phone_number');
        //         $table->boolean('confirm_no_hp')->default(false)->nullable();
        //         $table->timestamps();
                
        //         $table->index('id_user');
        //     });
        // }
        
        // if (!Schema::hasTable('user_data')) {
        //     Schema::create('user_data', function (Blueprint $table) {
        //         $table->uuid('id_user');
        //         $table->string('nik')->unique();
        //         $table->timestamps();
                
        //         $table->index('id_user');
        //     });
        // }
        
        // if (!Schema::hasTable('user_media')) {
        //     Schema::create('user_media', function (Blueprint $table) {
        //         $table->uuid('id_user_media')->primary();
        //         $table->uuid('id_user');
        //         $table->string('media_type'); // photo_profile, etc ....
        //         $table->uuid('id_file_disk');
        //         // $table->string('disk');
        //         // $table->string('path');
        //         // $table->string('file_name')->unique();
        //         $table->timestamps();
                 
        //         $table->index('id_user');
        //         $table->index('id_file_disk');
        //     });
        // }
        
        // if (!Schema::hasTable('user_social')) {
        //     Schema::create('user_social', function (Blueprint $table) {
        //         $table->uuid('id_user');
        //         $table->string('platform'); // e.g., 'Twitter', 'LinkedIn'
        //         $table->string('url');
        //         $table->string('bio')->nullable();
        //         $table->timestamps();
                
        //         $table->index('id_user');
        //     });
        // }
        
        if (!Schema::hasTable('users_remember_token')) { // remember token yang bisa dibanyak device seperti ada fitur switch akun kedepannya
            Schema::create('users_remember_token', function (Blueprint $table) {
                $table->uuid('id_user_remember_token')->primary();
                $table->uuid('id_user');
                $table->string('remember_token');
                $table->string('user_agent')->nullable(); // opsional
                $table->string('user_agent_hash')->nullable(); // opsional // just hash random string and encrypt in to cookies
                // $table->ipAddress('ip_address')->nullable(); // opsional
                $table->string('ip_address')->nullable(); // opsional
                $table->timestamp('last_used_at')->useCurrent();
                $table->timestamp('expired_at')->default(DB::raw('DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 30 DAY)'));

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
