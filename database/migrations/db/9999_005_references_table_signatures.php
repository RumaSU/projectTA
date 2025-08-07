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
        // Signatures
        Schema::table('signatures', function (Blueprint $table) {
            $table->foreign('id_user')
                ->references('id_user')->on('users')->onDelete('cascade');
        });
        
        Schema::table('signatures_type', function (Blueprint $table) {
            $table->foreign('id_signature')
                ->references('id_signature')->on('signatures')->onDelete('cascade');
        });
        
        Schema::table('signatures_drawings', function (Blueprint $table) {
            $table->foreign('id_signature_type')
                ->references('id_signature_type')->on('signatures_type')->onDelete('cascade');
        });
        
        Schema::table('signatures_files', function (Blueprint $table) {
            $table->foreign('id_signature_type', 'fk_sfile_sigtype')
                ->references('id_signature_type')->on('signatures_type')->onDelete('cascade');
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
