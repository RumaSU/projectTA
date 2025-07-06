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
        if (!Schema::hasTable('signatures')) { 
            Schema::create('signatures', function (Blueprint $table) {
                $table->uuid('id_signature')->primary();
                $table->uuid('id_user');
                $table->boolean('default')->default(0);
                $table->timestamps();
                
                $table->index('id_signature');
                $table->index('id_user');
            });
        }
        
        if (!Schema::hasTable('signatures_type')) { 
            Schema::create('signatures_type', function (Blueprint $table) {
                $table->uuid('id_signature_type')->primary();
                $table->uuid('id_signature');
                $table->enum('type', ['signature', 'paraf']);
                $table->timestamps();
                
                $table->index('id_signature_type');
                $table->index('id_signature');
            });
        }
        
        if (!Schema::hasTable('signatures_pad_data')) { 
            Schema::create('signatures_pad_data', function (Blueprint $table) {
                $table->uuid('id_signature_pad_data')->primary();
                $table->uuid('id_signature_type');
                $table->string('pad_key'); // key: original, 2x, thumbnail
                $table->string('pad_mime'); // image/png, image/xvg+xml 
                $table->longText('pad_base64'); // value: 
                $table->json('pad_points')->nullable();
                $table->timestamps();
                
                $table->index('id_signature_pad_data');
                $table->index('id_signature_type');
            });
        }
        
        if (!Schema::hasTable('signatures_file')) { 
            Schema::create('signatures_file', function (Blueprint $table) {
                $table->uuid('id_signature_file')->primary();
                $table->uuid('id_signature_type');
                $table->uuid('id_file_signature');
                $table->string('signature_file_key'); // original, 2x, thumbnail
                $table->timestamps();
                
                $table->index('id_signature_file');
                $table->index('id_signature_type');
                $table->index('id_file_signature');
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
