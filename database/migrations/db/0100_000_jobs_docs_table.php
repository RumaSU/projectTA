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
        if (!Schema::hasTable('app_jobs_process_docs')) {
            Schema::create('app_jobs_process_docs', function (Blueprint $table) {
                $table->uuid('id_app_jobs_process_docs')->primary();
                $table->uuid('id_user');
                $table->string('token')->unique()->index()->nullable(); // token untuk request cancel pada sisi user daripada memunculkan id, ini seperti identifier process
                
                $table->string('type_job'); // create, delete, update, new
                $table->enum('status', ['process', 'success', 'failed', 'cancelled', 'retried', 'completed'])->default('process');
                $table->enum('process_state', ['processable', 'blocked'])->default('processable');
                
                $table->json('payload')->nullable();
                $table->text('message')->nullable();
                $table->json('message_state')->nullable(); // [ {"message": message} ]
                
                $table->longText('retry_reason')->nullable();
                $table->boolean('retryable')->default(false);
                $table->unsignedTinyInteger('attempts')->default(0);
                
                $table->boolean('is_cancelled')->default(false);
                $table->json('exception')->nullable();
                
                $table->json('process_detail')->nullable(); // [ { "attempt": attempt, "step": step, "actor": system|user, "status": status, "message": message, "timestamp": timestamp, "exception": null }, ... ]
                
                $table->timestamp('expire_at')->nullable();
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
