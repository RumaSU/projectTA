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
        if (!Schema::hasTable('chunk')) {
            Schema::create('chunk', function(Blueprint $table) {
                $table->uuid('id_chunk')->primary();
                $table->string('chunk_table'); // chunk_document, chunk....table etc
                $table->uuid('chunk_id_table'); // uuid from chunk table
                $table->string('ip_address');
                $table->string('user_agent');
                $table->uuid('id_user');
                $table->timestamps();
                // $table->boolean('status')->default(true);
                // $table->text('error_reason')->nullable();
            });
        }
        
        if (!Schema::hasTable('chunk_document')) {
            Schema::create('chunk_document', function(Blueprint $table) {
                $table->uuid('id_chunk_document')->primary();
                $table->string('file_client_name');
                $table->string('file_hash_name');
                $table->string('file_type');
                $table->json('chunk_data'); // storage, chunk, .....
                // $table->unsignedBigInteger('total_chunks')->default(1);
                // $table->unsignedBigInteger('total_size');
                $table->boolean('status')->default(true);
                $table->text('error_reason')->nullable();
                $table->uuid('id_chunk');
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
