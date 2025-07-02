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
        if (!Schema::hasTable('files_signature')) {
            Schema::create('files_signature', function (Blueprint $table) {
                $table->uuid('id_file_signature')->primary();
                $table->uuid('id_user');
                
                $table->enum('type', ['signature', 'paraf']);
                $table->string('file_name', 512);
                $table->string('file_ext'); // png
                $table->string('file_path', 2048);
                $table->string('file_type');
                $table->integer('file_size'); // image/png
                
                $table->timestamps();
                
                $table->index('id_file_signature');
                $table->index('id_user');
                $table->index('file_path');
            });
        }
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
