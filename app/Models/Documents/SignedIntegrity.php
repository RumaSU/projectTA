<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class SignedIntegrity extends Model
{
    protected $table = 'documents_signed_integrity'; // Nama tabel
    protected $primaryKey = 'id_document_signed_integrity'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document_signed'
    ];
    protected $hidden = [
        'id_document_signed',
    ];

    protected $fillable = [
        'id_document_signed_integrity',
        'id_document_signed',
        'hash_type',
        'hash_value',
    ];
    protected function casts(): array
    {
        return [
            'id_document_signed_integrity' => 'string',
            'id_document_signed' => 'string',
        ];
    }
    
}
