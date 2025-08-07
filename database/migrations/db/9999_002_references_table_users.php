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
        
        // User
        Schema::table('users_personal', function (Blueprint $table) {
            $table->foreign('id_user')
                ->references('id_user')->on('users')->onDelete('cascade');
        });
        
        Schema::table('users_data', function (Blueprint $table) {
            $table->foreign('id_user')
                ->references('id_user')->on('users')->onDelete('cascade');
        });
        
        Schema::table('users_phone', function (Blueprint $table) {
            $table->foreign('id_user')
                ->references('id_user')->on('users')->onDelete('cascade');
        });
        
        Schema::table('users_social_media', function (Blueprint $table) {
            $table->foreign('id_user')
                ->references('id_user')->on('users')->onDelete('cascade');
        });
        
        Schema::table('users_account_status', function (Blueprint $table) {
            $table->foreign('id_user')
                ->references('id_user')->on('users')->onDelete('cascade');
        });
        
        Schema::table('users_remember_token', function (Blueprint $table) {
            $table->foreign('id_user')
                ->references('id_user')->on('users')->onDelete('cascade');
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
