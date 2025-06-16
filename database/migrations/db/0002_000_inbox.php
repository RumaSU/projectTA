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
        // if (!Schema::hasTable('inbox')) {
        //     Schema::create('inbox', function (Blueprint $table) {
        //         $table->uuid('id_inbox')->primary();
        //         $table->uuid('user_from');
        //         $table->uuid('user_to');
        //         $table->timestamps();
        //     });
        // }
        
        // if (!Schema::hasTable('inbox_content')) {
        //     Schema::create('inbox_content', function (Blueprint $table) {
        //         $table->uuid('id_inbox');
        //         $table->string('label_inbox');
        //         $table->string('description')->nullable();
        //         $table->timestamps();
        //     });
        // }
        
        // if (!Schema::hasTable('inbox_document')) { 
        //     Schema::create('inbox_document', function (Blueprint $table) {
        //         $table->uuid('id_inbox');
        //         $table->uuid('id_document');
        //         $table->timestamps();
        //     });
        // } 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbox');
        Schema::dropIfExists('inbox_content');
        Schema::dropIfExists('inbox_document');
    }
};
