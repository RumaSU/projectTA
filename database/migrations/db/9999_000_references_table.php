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
        
        
        
        
        // Documents
        Schema::table('documents', function (Blueprint $table) {
            $table->foreign('owner_id')
                ->references('id_user')->on('users')->onDelete('cascade');
        });

        // documents_information
        Schema::table('documents_information', function (Blueprint $table) {
            $table->foreign('id_document')
                ->references('id_document')->on('documents')->onDelete('cascade');
        });

        // documents_publicity
        Schema::table('documents_publicity', function (Blueprint $table) {
            $table->foreign('id_document')
                ->references('id_document')->on('documents')->onDelete('cascade');
        });

        // documents_collaborator
        Schema::table('documents_collaborator', function (Blueprint $table) {
            $table->foreign('id_document')
                ->references('id_document')->on('documents')->onDelete('cascade');
            
            $table->foreign('id_user')
                ->references('id_user')->on('users')->onDelete('cascade');
        });

        // documents_versions
        Schema::table('documents_versions', function (Blueprint $table) {
            $table->foreign('id_document')
                ->references('id_document')->on('documents')->onDelete('cascade');
        });

        // documents_file
        Schema::table('documents_file', function (Blueprint $table) {
            $table->foreign('id_document_version')
                ->references('id_document_version')->on('documents_versions')->onDelete('cascade');
            
            $table->foreign('id_file_document')
                ->references('id_file_document')->on('files_document')->onDelete('cascade');
        });

        // documents_signatures
        Schema::table('documents_signatures', function (Blueprint $table) {
            $table->foreign('id_document')
                ->references('id_document')->on('documents')->onDelete('cascade');
        });

        // documents_signatures_type
        Schema::table('documents_signatures_type', function (Blueprint $table) {
            $table->foreign('id_document_signature')
                ->references('id_document_signature')->on('documents_signatures')->onDelete('cascade');
        });

        // documents_signatures_status
        Schema::table('documents_signatures_status', function (Blueprint $table) {
            $table->foreign('id_document_signature')
                ->references('id_document_signature')->on('documents_signatures')->onDelete('cascade');
        });

        // documents_signatures_signer
        Schema::table('documents_signatures_signer', function (Blueprint $table) {
            $table->foreign('id_document_signature')
                ->references('id_document_signature')->on('documents_signatures')->onDelete('cascade');
            
            $table->foreign('id_document_collaborator')
                ->references('id_document_collaborator')->on('documents_collaborator')->onDelete('cascade');
            
            $table->foreign('id_signature_type')
                ->references('id_signature_type')->on('signatures_type')->onDelete('cascade');
            
        });

        // documents_signatures_signer_permission
        Schema::table('documents_signatures_signer_permission', function (Blueprint $table) {
            $table->foreign('id_document_signature')
                ->references('id_document_signature')->on('documents_signatures')->onDelete('cascade');
            
            $table->foreign('id_document_collaborator')
                ->references('id_document_collaborator')->on('documents_collaborator')->onDelete('cascade');
            
        });

        // documents_signatures_signer_order
        Schema::table('documents_signatures_signer_order', function (Blueprint $table) {
            $table->foreign('id_document_signature')
                ->references('id_document_signature')->on('documents_signatures')->onDelete('cascade');
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
        
        Schema::table('files_document', function (Blueprint $table) {
            $table->foreign('owner_id')
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
