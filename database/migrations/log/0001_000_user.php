<?php

use Illuminate\Support\Facades\DB; 

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
        if (!Schema::hasTable('logs_user')) {
            Schema::create('logs_user', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('id_user')->index();
                $table->string('identifier')->unique()->nullable()->index();
                
                $table->string('title_log');
                $table->tinyText('desc_log');
                $table->string('type', 50); // auth, update, delete, upload, job, ....
                $table->string('action', 50); // login, logout, updated_profile, deleted_document ...
                $table->string('actor')->default('user'); // user atau system
                
                /**
                 * "label",
                 * "field",
                 * "list":
                 *    "label"
                 *    "field"
                 *    "old"
                 *    "new"
                 * "old" // optional,
                 * "new" // optional,
                 */
                $table->json('payload')->nullable(); // detail informasi
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
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
