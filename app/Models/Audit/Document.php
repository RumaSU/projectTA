<?php

namespace App\Models\Audit;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $table = 'documents_audit_logs'; // Nama tabel
    protected $primaryKey = 'id_document_audit_log'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [
        'id_document_audit_log'
    ];
    protected $hidden = [
        'id_document',
        'id_user',
    ];

    protected $fillable = [
        'id_document_audit_log',
        'id_document',
        'id_user',
        'category',
        'event_type',
        'description',
        'metadata',
        'logged_at',
        'data_hash'
    ];
    
    protected function casts(): array
    {
        return [
            'id_document_audit_log' => 'string',
            'id_document' => 'string',
            'id_user' => 'string',
            'metadata' => 'string',
        ];
    }
}
