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
        
        // Main data documents
        if (!Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->uuid('id_document')->primary();
                $table->uuid('owner_id')->index();
                $table->boolean('is_delete')->default(false);
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('documents_information')) {
            Schema::create('documents_information', function (Blueprint $table) {
                $table->uuid('id_document')->primary();
                $table->string('name', 512);
                $table->integer('name_version')->default(1); // easier check duplicate name, ex {name . v ? (v) : ''}
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('documents_publicity')) {
            Schema::create('documents_publicity', function (Blueprint $table) {
                $table->uuid('id_document')->primary();
                $table->string('status_publicity')->default('private'); // 'public', 'private'
                $table->timestamp('status_changed')->useCurrent()->useCurrentOnUpdate();
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('documents_collaborator')) {
            Schema::create('documents_collaborator', function (Blueprint $table) { // auto fill with owner
                $table->uuid('id_document_collaborator')->primary();
                $table->uuid('id_document')->index();
                $table->uuid('id_user')->index();
                $table->string('role')->default('viewer'); // viewer, signer
                $table->timestamp('role_changed')->useCurrent()->useCurrentOnUpdate();
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('documents_versions')) {
            Schema::create('documents_versions', function (Blueprint $table) {
                $table->uuid('id_document_version')->primary();
                $table->uuid('id_document')->index();
                $table->integer('version')->default(1);
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('documents_file')) {
            Schema::create('documents_file', function (Blueprint $table) {
                $table->uuid('id_document_file')->primary();
                $table->uuid('id_document_version')->index();
                $table->uuid('id_file_document')->index();
                $table->timestamps();
            });
        }
        
        
        if (! Schema::hasTable('documents_audit_logs')) {
            Schema::create('documents_audit_logs', function (Blueprint $table) {
                $table->uuid('id_document_audit_log')->primary();
                $table->uuid('id_document')->index();
                $table->uuid('id_user')->nullable();
                
                $table->string('category')->index(); // document, signature, audit
                $table->string('event_type')->index(); // 'created', 'updated', 'archived', 'status_changed', 'signed', etc.
                
                $table->text('description')->nullable();
                $table->json('metadata')->nullable();
                
                $table->timestamp('logged_at')->useCurrent();
                $table->char('data_hash', 128);
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
