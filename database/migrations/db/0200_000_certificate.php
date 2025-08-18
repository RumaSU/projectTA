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
        if (!Schema::hasTable('certificates')) {
            Schema::create('certificates', function (Blueprint $table) {
                $table->uuid('id_certificate')->primary();
                $table->uuid('id_user');
                $table->text('certificate');
                $table->text('private_key');
                $table->dateTime('valid_from');
                $table->dateTime('valid_to');
                $table->string('serial_number')->nullable();
                $table->string('fingerprint')->nullable(); 
                $table->string('fingerprint_type')->nullable(); // by Hash enum
                $table->string('issuer')->default('self-signed');
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('certificates_identity')) {
            Schema::create('certificates_identity', function (Blueprint $table) {
                $table->uuid('id_certificate_identity')->primary();
                $table->uuid('id_certificate');
                $table->string('country_name')->nullable();
                $table->string('state_or_province_name')->nullable();
                $table->string('locality_name')->nullable();
                $table->string('organization_name')->nullable();
                $table->string('organizational_unit_name')->nullable();
                $table->string('common_name')->nullable();
                $table->string('email_address')->nullable();
                $table->timestamps();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ...
    }
};
