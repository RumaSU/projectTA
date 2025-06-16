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
        if (!Schema::hasTable('subscription')) {
            Schema::create('subscription', function (Blueprint $table) {
                $table->uuid('id_subscription')->primary();
                $table->json('subscription_token'); // isi kumpulan data json yang akan di encrypt
                $table->string('subcription_type'); // isinya ada banyak mungkin profile, documents atau subscription lainnya
                $table->timestamp('expires_date')->nullable();
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('user_subscription')) {
            Schema::create('user_subscription', function (Blueprint $table) {
                $table->uuid('id_user_subscription')->primary();
                $table->uuid('id_user');
                $table->uuid('id_subscription');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription');
        Schema::dropIfExists('user_subscription');
    }
};
