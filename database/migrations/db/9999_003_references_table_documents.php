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
            
            // $table->foreign('id_file_document')
            //     ->references('id_file_document')->on('files_documents')->onDelete('cascade');
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
