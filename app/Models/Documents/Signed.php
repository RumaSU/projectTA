<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class Signed extends Model
{
    protected $table = 'documents_signed'; // Nama tabel
    protected $primaryKey = 'id_document_signed'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document',
        'id_document_signature'
    ];
    protected $hidden = [
        'id_document',
        'id_document_signature'
    ];

    protected $fillable = [
        'id_document_signed',
        'id_document',
        'id_document_signature',
        'id_certificate',
        'signed_at'
    ];
    protected function casts(): array
    {
        return [
            'id_document_signed' => 'string',
            'id_document' => 'string',
            'id_document_signature' => 'string',
            'id_certificate' => 'string'
        ];
    }
    
}
