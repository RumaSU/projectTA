<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        // DB::statement('
        //     DELIMITER $$
                
        //         CREATE EVENT e_delete_token_signature_initials
        //             ON SCHEDULE EVERY 5 SECOND
        //         DO
        //         BEGIN
        //             DELETE FROM token_signature_initial WHERE expired_at < CONVERT_TZ(NOW(), @@session.time_zone, "+00:00");
        //             DELETE FROM token_signature_initial WHERE is_used = TRUE;
        //         END $$

        //     DELIMITER ;
        // ');
        DB::statement(
            'DROP EVENT IF EXISTS e_delete_token_signature_initials'
        );
        DB::statement('
            CREATE EVENT e_delete_token_signature_initials
                ON SCHEDULE EVERY 25 MINUTE
            DO
            BEGIN
                DELETE FROM token_signature_initial WHERE expired_at < CONVERT_TZ(NOW(), @@session.time_zone, "+00:00");
                DELETE FROM token_signature_initial WHERE is_used = TRUE;
            END ;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
