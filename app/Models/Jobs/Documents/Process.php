<?php

namespace App\Models\Jobs\Documents;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    protected $table = 'app_jobs_process_docs'; // Nama tabel
    protected $primaryKey = 'id_app_jobs_process_docs';
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_app_jobs_process_docs',
    ];
    protected $hidden = [
        'id_user',
    ];
    
    protected $fillable = [
        'id_app_jobs_process_docs',
        'id_user',
        'token',
        'type_job', // new, update, delete, 
        'status', // 'process', 'success', 'failed', 'cancelled', 'retried'
        'process_state', // 'processable', 'blocked'
        'payload',
        'message', // common message
        'message_state', // [ ..., ... ]
        'retry_reason',
        'retryable',
        'attempts',
        'is_cancelled',
        'exception', // latest exception
        'process_detail', // { { "attempt": attempt, "step": step, "actor": system|user, "status": status, "message": message, "timestamp": timestamp, "exception": null }, ... }
        'expire_at',
    ];
    
    protected function casts(): array
    {
        return [
            'id_app_jobs_process_docs' => 'string',
            'id_user' => 'string',
            'payload' => 'json',
            'process_detail' => 'json',
            'message_state' => 'array',
            'exception' => 'array',
        ];
    }
    
}
