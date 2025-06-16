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
        // if (!Schema::hasTable('token_user')) {
        //     Schema::create('token_user', function (Blueprint $table) {
        //         $table->uuid('id_token_user')->primary();
        //         $table->uuid('id_user');
        //         $table->string('token')->unique();
        //         $table->string('type_token'); // signature, initital, .....
        //         $table->timestamp('expired_at');
        //         $table->timestamps();
                
        //         $table->index('id_user_token');
        //         $table->index('id_user');
        //         $table->index('token');
        //     });
        // }
        
        if (!Schema::hasTable('token_signature_initial')) {
            Schema::create('token_signature_initial', function (Blueprint $table) {
                $table->uuid('id_token_signature_initial')->primary();
                $table->uuid('id_document');
                $table->uuid('id_user');
                $table->string('token')->unique();
                $table->enum('type_sign', ['signature', 'initials']);
                $table->boolean('is_used')->default(false);
                $table->timestamp('expired_at');
                $table->timestamps();
                
                $table->index('id_token_signature_initial');
                $table->index('id_document');
                $table->index('id_user');
                $table->index('token');
            });
        }
        
        // if (!Schema::hasTable('token_signature_initial')) {
        //     Schema::create('token_signature_initial', function (Blueprint $table) {
        //         $table->uuid('id_token_signature_initial')->primary();
        //         $table->uuid('id_user');
        //         $table->string('token')->unique();
        //         $table->string('type_token'); // signature, initital, .....
        //         $table->timestamp('expired_at');
        //         $table->timestamps();
                
        //         $table->index('id_user_token');
        //         $table->index('id_user');
        //         $table->index('token');
        //     });
        // }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_user');
        Schema::dropIfExists('token_signature_initial');
    }
};
