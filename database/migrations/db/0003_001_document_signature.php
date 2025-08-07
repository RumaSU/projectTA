<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Data documents when signatures
        if (!Schema::hasTable('documents_signatures')) {
            Schema::create('documents_signatures', function (Blueprint $table) {
                $table->uuid('id_document_signature')->primary();
                $table->uuid('id_document')->index();
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('documents_signatures_type')) {
            Schema::create('documents_signatures_type', function (Blueprint $table) {
                $table->uuid('id_document_signature')->primary();
                $table->string('type')->default('uncategorized'); // 'signature', 'paraf', 'uncategorized'
                $table->timestamp('type_changed')->nullable();
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('documents_signatures_status')) {
            Schema::create('documents_signatures_status', function (Blueprint $table) {
                $table->uuid('id_document_signature')->primary();
                $table->string('status')->default('draft'); //'progress', 'completed', 'rejected', 'draft'
                $table->timestamp('status_changed')->useCurrent()->useCurrentOnUpdate();
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('documents_signatures_signer')) {
            Schema::create('documents_signatures_signer', function (Blueprint $table) {
                $table->uuid('id_document_signature_signer')->primary();
                $table->uuid('id_document_signature')->index();
                $table->uuid('id_document_collaborator')->index();
                $table->uuid('id_signature_type')->nullable()->index();
                
                $table->json('signing_metadata')->nullable(); // post{x, y}, device, browser, ip, 
                $table->timestamp('signed_at')->nullable(); // waktu ditandatangani
                
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('documents_signatures_permission')) {
            Schema::create('documents_signatures_permission', function (Blueprint $table) {
                $table->uuid('id_document_signature_permission')->primary();
                $table->uuid('id_document_signature')->index();
                $table->uuid('id_document_collaborator')->index();
                $table->string('permission')->default('validate'); // 'validate', 'finalize', 'sign'
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('documents_signatures_flows')) {
            Schema::create('documents_signatures_flows', function (Blueprint $table) {
                $table->uuid('id_document_signature_flows')->primary();
                
                $table->uuid('id_document_signature')->index();
                $table->uuid('id_dc_sign_permission')->index();
                
                $table->unsignedInteger('sequence_order');
                $table->timestamp('expired_at')->nullable(); // auto insert to next signer
                $table->timestamp('completed_at')->nullable();
                
                $table->string('expired_action')->nullable(); // skipped, blocked
                $table->timestamp('expired_handled_at')->nullable();
                
                $table->json('metadata')->nullable(); // {}
                
                $table->boolean('is_skipped')->default(false);
                
                $table->timestamps();
            });
        }
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document');
    }
};
