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
                $table->uuid('id_user')->index();
                $table->boolean('is_default')->default(0);
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('signatures_type')) { 
            Schema::create('signatures_type', function (Blueprint $table) {
                $table->uuid('id_signature_type')->primary();
                $table->uuid('id_signature')->index();
                $table->enum('type', ['signature', 'paraf']);
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('signatures_drawings')) { 
            Schema::create('signatures_drawings', function (Blueprint $table) {
                $table->uuid('id_signature_drawing')->primary();
                $table->uuid('id_signature_type')->index();
                $table->string('variant')->index(); // key: original, 2x, thumbnail
                $table->string('mime_type'); // image/png, image/xvg+xml 
                $table->longText('base64_data'); // value: 
                $table->json('points')->nullable();
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('signatures_files')) { 
            Schema::create('signatures_files', function (Blueprint $table) {
                $table->uuid('id_signature_file')->primary();
                $table->uuid('id_signature_type')->index();
                $table->uuid('id_file_signature')->index();
                $table->string('variant')->index(); // original, 2x, thumbnail
                $table->timestamps();
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
