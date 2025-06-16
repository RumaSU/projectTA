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
        if (!Schema::hasTable('user_profile')) {
            Schema::create('user_profile', function (Blueprint $table) {
                $table->uuid('id_user_profile')->primary();
                $table->uuid('id_user');
                $table->string('profile_name');
                $table->string('company');
                $table->string('employment');
                $table->boolean('status')->default(1);
                $table->boolean('locked')->default(0);
                $table->timestamps();
                
                $table->index('id_user');
                $table->index('id_user_profile');
            });
        }
        
        if (!Schema::hasTable('user_profile_quota')) {
            Schema::create('user_profile_quota', function (Blueprint $table) {
                $table->uuid('id_user');
                $table->unsignedBigInteger('quota_limit')->default(2);
                $table->unsignedBigInteger('quota_used')->default(0);
                $table->timestamps();
                
                $table->index('id_user');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile');
        Schema::dropIfExists('user_profile_quota');
    }
};
