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
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->uuid('id_user')->primary();
                $table->string('email')->unique();
                $table->string('username')->unique();
                $table->string('password');
                $table->timestamps();
                
                $table->index('id_user');
                $table->index('email');
                $table->index('username');
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
