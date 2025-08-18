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
        Schema::table('certificates', function (Blueprint $table) {
            $table->foreign('id_user')
                ->references('id_user')->on('users')->onDelete('cascade');
        });

        Schema::table('certificates_identity', function (Blueprint $table) {
            $table->foreign('id_certificate')
                ->references('id_certificate')->on('certificates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ...
    }
};
