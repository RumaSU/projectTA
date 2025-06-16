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
        if (!Schema::hasTable('signature')) { 
            Schema::create('signature', function (Blueprint $table) {
                $table->uuid('id_signature')->primary();
                $table->uuid('id_user');
                $table->string('title_signature')->nullable();
                // $table->string('file_name_signature');
                $table->string('unique_key')->unique();
                $table->boolean('default')->default(0);
                // $table->string('pin');
                $table->timestamps();
                
                $table->index('id_signature');
                $table->index('id_user');
            });
        }
        
        if (!Schema::hasTable('signature_disk')) { 
            Schema::create('signature_disk', function (Blueprint $table) {
                $table->uuid('id_signature_disk')->primary();
                $table->uuid('id_signature');
                $table->string('disk');
                $table->string('path');
                $table->string('file_name')->unique();
                // $table->string('pin');
                $table->timestamps();
                
                $table->index('id_signature');
            });
        }
        
        // if (!Schema::hasTable('signature_pin')) {
        //     Schema::create('signature_pin', function (Blueprint $table) {
        //         $table->uuid('id_pin')->primary();
        //         $table->uuid('id_signature');
        //         $table->boolean('status')->default(false);
        //         $table->string('pin')->nullable();
        //         $table->timestamps();
        //     });
        // }
        
        if (!Schema::hasTable('signature_document')) {
            Schema::create('signature_document', function (Blueprint $table) {
                $table->uuid('id_signature_document');
                $table->uuid('id_user');
                $table->uuid('id_signature');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signature');
        Schema::dropIfExists('signature_document');
    }
};
