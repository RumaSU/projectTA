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
        Schema::create('log_user', function(Blueprint $table) { 
            $table->uuid('id')->primary();
            $table->uuid('id_user');
            $table->json('action')->nullable();
            $table->string('method')->nullable(); // get, post, ....
            $table->string('ip_address')->nullable(); // alamat IP user
            $table->text('user_agent')->nullable(); // info browser dan sistem
            $table->timestamps();
        });
        
        // Schema::create('log_user_auth', function(Blueprint $table) { 
        //     $table->id();
        //     $table->uuid('id_user')->nullable();
        //     $table->string('action')->nullable(); // login atau logout
        //     $table->string('ip_address')->nullable(); // alamat IP user
        //     $table->text('user_agent')->nullable(); // info browser dan sistem
        //     $table->timestamps();
        // });
        
        Schema::create('log_user_activity', function(Blueprint $table) { 
            $table->uuid('id')->primary();
            $table->uuid('id_user');
            $table->json('activity_type')->nullable(); // Account, Settings, Documents, Signatures, 'Files
            $table->json('action')->nullable();
            $table->string('ip_address')->nullable(); // alamat IP user
            $table->text('user_agent')->nullable(); // info browser dan sistem
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_user');
        Schema::dropIfExists('log_user_auth');
    }
};
