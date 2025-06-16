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
        Schema::table('user_personal', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });

        Schema::table('user_data', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
            // $table->unique('nik');
        });
        
        Schema::table('user_media', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });
        
        Schema::table('user_social', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });

        // Schema::table('inbox_content', function (Blueprint $table) {
        //     $table->foreign('id_inbox')->references('id_inbox')->on('inbox')->onDelete('cascade');
        // });

        // Schema::table('inbox_document', function (Blueprint $table) {
        //     $table->foreign('id_inbox')->references('id_inbox')->on('inbox')->onDelete('cascade');
        //     $table->foreign('id_document')->references('id_document')->on('document')->onDelete('cascade');
        // });

        Schema::table('document', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });
        
        // Schema::table('document_disk', function (Blueprint $table) {
        //     $table->foreign('id_document')->references('id_document')->on('document')->onDelete('cascade');
        // });
        
        Schema::table('document_versions', function (Blueprint $table) {
            $table->foreign('id_document')->references('id_document')->on('document')->onDelete('cascade');
        });
        
        Schema::table('document_shared_access', function (Blueprint $table) {
            $table->foreign('id_document')->references('id_document')->on('document')->onDelete('cascade');
        });
        
        Schema::table('document_shared_access_user', function (Blueprint $table) {
            $table->foreign('id_document')->references('id_document')->on('document')->onDelete('cascade');
        });
        
        Schema::table('document_signed', function (Blueprint $table) {
            $table->foreign('id_document')->references('id_document')->on('document')->onDelete('cascade');
        });
        
        Schema::table('document_signed_data', function (Blueprint $table) {
            $table->foreign('id_document_signed')->references('id_document_signed')->on('document_signed')->onDelete('cascade');
        });

        Schema::table('document_signed_access', function (Blueprint $table) {
            $table->foreign('id_document')->references('id_document')->on('document')->onDelete('cascade');
            // $table->foreign('user_access')->references('id_user')->on('user')->onDelete('cascade');
        });
        
        Schema::table('document_signed_access_user', function (Blueprint $table) {
            $table->foreign('id_document')->references('id_document')->on('document')->onDelete('cascade');
            $table->foreign('user_access')->references('id_user')->on('user')->onDelete('cascade');
        });

        Schema::table('signature', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });

        Schema::table('signature_disk', function (Blueprint $table) {
            $table->foreign('id_signature')->references('id_signature')->on('signature')->onDelete('cascade');
        });
        
        
        // Schema::table('signature_pin', function (Blueprint $table) {
        //     $table->foreign('id_signature')->references('id_signature')->on('signature')->onDelete('cascade');
        // });

        Schema::table('signature_document', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
            $table->foreign('id_signature')->references('id_signature')->on('signature')->onDelete('cascade');
        });

        Schema::table('initial', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });

        Schema::table('initial_disk', function (Blueprint $table) {
            $table->foreign('id_initial')->references('id_initial')->on('initial')->onDelete('cascade');
        });

        Schema::table('initial_document', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
            $table->foreign('id_initial')->references('id_initial')->on('initial')->onDelete('cascade');
        });

        Schema::table('user_profile', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });

        Schema::table('user_profile_quota', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });

        Schema::table('user_subscription', function (Blueprint $table) {
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
            $table->foreign('id_subscription')->references('id_subscription')->on('subscription')->onDelete('cascade');
        });
        
        
        Schema::table('file_disk_entity', function (Blueprint $table) {
            $table->foreign('id_file_disk')->references('id_file_disk')->on('file_disk')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_data', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
        });

        Schema::table('user_personal', function (Blueprint $table) {
            $table->dropForeign(['id_personal']);
        });

        Schema::table('user', function (Blueprint $table) {
            $table->dropPrimary(['id_user']);
        });

        Schema::table('inbox', function (Blueprint $table) {
            $table->dropPrimary(['id_inbox']);
        });

        Schema::table('inbox', function (Blueprint $table) {
            $table->dropForeign(['user_from']);
        });

        Schema::table('inbox', function (Blueprint $table) {
            $table->dropForeign(['user_to']);
        });

        Schema::table('inbox_content', function (Blueprint $table) {
            $table->dropForeign(['id_inbox']);
        });

        Schema::table('inbox_document', function (Blueprint $table) {
            $table->dropForeign(['id_inbox']);
        });

        Schema::table('inbox_document', function (Blueprint $table) {
            $table->dropForeign(['document']);
        });

        Schema::table('document', function (Blueprint $table) {
            $table->dropPrimary(['document']);
        });

        Schema::table('inbox_document', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
        });

        Schema::table('signature', function (Blueprint $table) {
            $table->dropPrimary(['id_signature']);
        });

        Schema::table('signature', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
        });
        
        // Schema::table('signature_pin', function (Blueprint $table) {
        //     $table->dropForeign(['id_signature']);
        // });

        Schema::table('signature_document', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
        });

        Schema::table('signature_document', function (Blueprint $table) {
            $table->dropForeign(['id_signature']);
        });

        Schema::table('initial', function (Blueprint $table) {
            $table->dropPrimary(['id_initial']);
        });

        Schema::table('initial', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
        });

        Schema::table('initial_document', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
        });

        Schema::table('initial_document', function (Blueprint $table) {
            $table->dropForeign(['id_initial']);
        });

        Schema::table('user_profile', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
        });
        
        Schema::table('user_profile', function (Blueprint $table) {
            $table->dropPrimary(['id_user_profile']);
        });

        Schema::table('user_profile_quota', function (Blueprint $table) {
            $table->foreign(['id_user']);
        });

        Schema::table('user_subscription', function (Blueprint $table) {
            $table->foreign(['id_user']);
            $table->foreign(['id_subscription']);
        });
    }
};
