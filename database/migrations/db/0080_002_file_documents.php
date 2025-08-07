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
        
        // File documents
        if (!Schema::hasTable('files_document')) {
            Schema::create('files_document', function (Blueprint $table) {
                $table->uuid('id_file_document')->primary();
                $table->uuid('owner_id');
                
                $table->string('file_name', length: 512);
                $table->string('file_ext'); // 
                $table->string('file_path', 2048);
                $table->string('file_mime'); ///png
                $table->integer('file_size'); 
                
                $table->timestamps();
                
                $table->index('owner_id');
                $table->index('file_path');
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
