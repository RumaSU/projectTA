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
        if (! Schema::hasTable('documents_signed')) {
            Schema::create('documents_signed', function (Blueprint $table) {
                $table->uuid('id_document_signed')->primary();
                $table->uuid('id_document')->index();
                $table->uuid('id_document_signature')->index();
                $table->uuid('id_certificate')->nullable()->index();
                $table->timestamp('signed_at')->nullable();
                $table->timestamps();
            });
        }
        
        
        if (! Schema::hasTable('documents_signed_integrity')) {
            Schema::create('documents_signed_integrity', function (Blueprint $table) {
                $table->uuid('id_document_signed_integrity')->primary();
                $table->uuid('id_document_signed')->index();
                $table->string('hash_type', 64);
                $table->string('hash_value', 512);
                $table->timestamps();
            });
        }
        
        if (! Schema::hasTable('documents_signed_qr')) {
            Schema::create('documents_signed_qr', function (Blueprint $table) {
                $table->uuid('id_document_signed_qr')->primary();
                $table->uuid('id_document_signed')->index();
                $table->string('identifier', 512);
                $table->timestamps();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_signed');
        Schema::dropIfExists('document_signed_integrity');
    }
};
