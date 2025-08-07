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
        // Files
        // Schema::table('files_signatures', function (Blueprint $table) {
        //     $table->foreign('id_user')
        //         ->references('id_user')->on('users')->onDelete('cascade');
        // });
        
        // Schema::table('files_documents', function (Blueprint $table) {
        //     $table->foreign('owner_id')
        //         ->references('id_user')->on('users')->onDelete('cascade');
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
