<?php

use Illuminate\Support\Facades\DB; 

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
        
        
        if (!Schema::hasTable('token_upload')) {
            Schema::create('token_upload', function (Blueprint $table) {
                $table->id('id_token_upload')->primary();
                $table->string('session_id');
                $table->string('token');
                $table->string('token_resumable');
                $table->boolean('used')->default(false);
                $table->timestamp('expired_at');
                $table->timestamps();
                
                $table->index('id_token_upload');
                $table->index('session_id');
                $table->index('token');
                $table->index('token_resumable');
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
