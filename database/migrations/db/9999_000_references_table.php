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
        // Session
        Schema::table('sessions', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id_user')->on('users')->onDelete('cascade');
        });
        
        
        
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
        
        
        
        
        
        
        // Signatures
        Schema::table('signatures', function (Blueprint $table) {
            $table->foreign('id_user')
                ->references('id_user')->on('users')->onDelete('cascade');
        });
        
        Schema::table('signatures_type', function (Blueprint $table) {
            $table->foreign('id_signature')
                ->references('id_signature')->on('signatures')->onDelete('cascade');
        });
        
        Schema::table('signatures_pad_data', function (Blueprint $table) {
            $table->foreign('id_signature_type')
                ->references('id_signature_type')->on('signatures_type')->onDelete('cascade');
        });
        
        Schema::table('signatures_file', function (Blueprint $table) {
            $table->foreign('id_signature_type', 'fk_sfile_sigtype')
                ->references('id_signature_type')->on('signatures_type')->onDelete('cascade');
                
            $table->foreign('id_file_signature', 'fk_sfile_fsig')
                ->references('id_file_signature')->on('files_signature')->onDelete('cascade');
        });
        
        
        
        
        // Files
        Schema::table('files_signature', function (Blueprint $table) {
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
