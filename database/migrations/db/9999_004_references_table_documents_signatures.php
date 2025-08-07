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
        Schema::table('documents_signatures_permission', function (Blueprint $table) {
            $table->foreign('id_document_signature')
                ->references('id_document_signature')->on('documents_signatures')->onDelete('cascade');
            
            $table->foreign('id_document_collaborator')
                ->references('id_document_collaborator')->on('documents_collaborator')->onDelete('cascade');
            
        });

        // documents_signatures_signer_order
        // Schema::table('documents_signatures_signer_order', function (Blueprint $table) {
        //     $table->foreign('id_document_signature')
        //         ->references('id_document_signature')->on('documents_signatures')->onDelete('cascade');
        // });
        
        // documents_signatures_flows
        Schema::table('documents_signatures_flows', function (Blueprint $table) {
            $table->foreign('id_document_signature')
                ->references('id_document_signature')->on('documents_signatures')->onDelete('cascade');
            
            $table->foreign('id_dc_sign_permission')
                ->references('id_document_signature_permission')->on('documents_signatures_permission')->onDelete('cascade');
            
            
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
