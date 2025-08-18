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
        Schema::table('documents_signed', function (Blueprint $table) {
            $table->foreign('id_document')
                ->references('id_document')->on('documents')->onDelete('cascade');
                
            $table->foreign('id_document_signature')
                ->references('id_document_signature')->on('documents_signatures')->onDelete('cascade');
                
            $table->foreign('id_certificate')
                ->references('id_certificate')->on('certificates')->onDelete('set null');
            
        });
        
        Schema::table('documents_signed_integrity', function (Blueprint $table) {
            $table->foreign('id_document_signed')
                ->references('id_document_signed')->on('documents_signed')->onDelete('cascade');
        });
        
        Schema::table('documents_signed_qr', function (Blueprint $table) {
            $table->foreign('id_document_signed')
                ->references('id_document_signed')->on('documents_signed')->onDelete('cascade');
        });
        // Schema::table('certificates_identity', function (Blueprint $table) {
        //     $table->foreign('id_certificate')
        //         ->references('id_certificate')->on('certificates')->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ...
    }
};
