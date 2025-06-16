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
        if (!Schema::hasTable('document')) {
            Schema::create('document', function (Blueprint $table) {
                $table->uuid('id_document')->primary();
                $table->uuid('id_user');
                $table->enum('signature_type', ['Signature', 'Initials'])->nullable();
                $table->enum('document_status', ['Draft', 'Pending', 'Approved', 'Rejected'])->default('Draft');
                // $table->enum('document_status', ['Draft', 'Pending', 'Expired', 'Approved', 'Rejected'])->default('Draft');
                $table->json('status_at'); // {done_at: timestamp, .....}
                $table->timestamps();
                
                $table->index('id_document');
                $table->index('id_user');
            });
        }
        
        // if (!Schema::hasTable('document_disk')) {
        //     Schema::create('document_disk', function (Blueprint $table) {
        //         $table->uuid('id_document_disk')->primary();
        //         $table->uuid('id_document');
        //         $table->string('disk');
        //         $table->string('path');
        //         $table->string('file_name')->unique();
        //         $table->string('client_name');
        //         $table->string('extension');
        //         $table->integer('version')->default(1);
        //         $table->timestamps();
        //     });
        // }
        if (!Schema::hasTable('document_versions')) {
            Schema::create('document_versions', function (Blueprint $table) {
                $table->uuid('id_document_versions')->primary(); // v7
                $table->uuid('id_document');
                // $table->uuid('id_file_disk');
                $table->uuid('id_file_disk_entity');
                $table->bigInteger('version')->default(1);
                $table->json('changes')->nullable();
                $table->timestamps();
                
                $table->index('id_document_versions');
                $table->index('id_document');
                $table->index('id_file_disk_entity');
            });
        }
        
        if (!Schema::hasTable('document_shared_access')) {
            Schema::create('document_shared_access', function (Blueprint $table) {
                $table->uuid('id_document_shared_access')->primary();
                $table->uuid('id_document');
                $table->boolean('is_shared')->default(false);
                $table->enum('access_role', ['Read', 'Edit'])->default('Read')->nullable();
                $table->timestamps();
                
                $table->index('id_document_shared_access');
                $table->index('id_document');
            });
        }
        
        if (!Schema::hasTable('document_shared_access_user')) {
            Schema::create('document_shared_access_user', function (Blueprint $table) {
                $table->uuid('id_document_shared_access_user')->primary();
                $table->uuid('id_document');
                $table->uuid('id_user');
                $table->enum('access_role', ['Read', 'Edit'])->default('Read')->nullable();
                $table->timestamps();
                
                $table->index('id_document_shared_access_user');
                $table->index('id_document');
            });
        }
        
        if (!Schema::hasTable('document_signed')) {
            Schema::create('document_signed', function (Blueprint $table) {
                $table->uuid('id_document_signed')->primary();
                $table->uuid('id_document');
                $table->uuid('signed_by'); // uuid user
                $table->json('signed_data'); // data json {'signature_type': 'Signature'|'Initials', 'signature_id/initial_id':'id_signature|id_initial'} 
                $table->timestamp('signed_at')->nullable();
                $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
                $table->json('status_at'); // {pending_at: timestamp, .....}
                $table->text('reason_rejected')->nullable();
                $table->timestamps();
                
                $table->index('id_document_signed');
                $table->index('id_document');
            });
        }
        
        if (!Schema::hasTable('document_signed_data')) {
            Schema::create('document_signed_data', function (Blueprint $table) {
                $table->uuid('id_document_signed_data')->primary();
                $table->uuid('id_document_signed'); //
                $table->string('signer_ip')->nullable(); // alamat IP user
                $table->text('signer_user_agent')->nullable(); // info browser dan sistem
                $table->string('location')->nullable(); // Lokasi tanda tangan
                // $table->text('device_info')->nullable(); // Informasi perangkat
            });
        }
        
        if (!Schema::hasTable('document_signed_access')) {
            Schema::create('document_signed_access', function (Blueprint $table) {
                $table->uuid('id_document_signed_access')->primary();
                $table->uuid('id_document');
                $table->boolean('is_shared')->default(false);
                $table->enum('access_role', ['Read', 'Edit'])->default('Read')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('document_signed_access_user')) {
            Schema::create('document_signed_access_user', function (Blueprint $table) {
                $table->uuid('id_document_signed_access_user')->primary();
                $table->uuid('id_document');
                $table->uuid('user_access');
                $table->enum('access_role', ['Read', 'Edit'])->default('Read');
                $table->timestamps();
            });
        }
        
        // if (!Schema::hasTable('document_withdraw')) {
        //     Schema::create('document_withdraw', function (Blueprint $table) {
        //         $table->uuid('id_document_withdraw')->primary();
        //         $table->uuid('id_document');
        //         $table->uuid('id_user'); // milik user
        //         $table->string('reason_withdraw')->nullable();
        //         $table->string('withdraw_ip')->nullable();
        //         $table->text('withdraw_user_agent')->nullable();
        //         $table->timestamps();
        //     });
        // }
        
        if (!Schema::hasTable('document_qr')) {
            Schema::create('document_qr', function (Blueprint $table) {
                $table->uuid('id_qr')->primary();
                $table->string('qr_identifier')->unique(); // untuk url saat mencari document menggunakan scann qr (random string)
                $table->json('data_qr'); // encrypt json
                $table->uuid('id_document');
                $table->timestamps();
            });
        }
        
        if (!Schema::hasTable('document_user_profile')) {
            Schema::create('document_user_profile', function (Blueprint $table) {
                $table->uuid('id_document_user_profile')->primary();
                $table->uuid('id_document');
                $table->uuid('id_user_profile');
                $table->timestamps();
            });
        }
        
        // apakah dari tabel document_signed berarti jika ada 2 user yang melakukan tandatangan dan salah satunya ada yang reject maka status document bisa saja menjadi pending dan bisa melakukan pengiriman ulang untuk tanda tangan dan approve, 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document');
    }
};
