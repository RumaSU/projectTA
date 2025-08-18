<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class SignedQR extends Model
{
    protected $table = 'documents_signed_qr'; // Nama tabel
    protected $primaryKey = 'id_document_signed_qr'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document_signed'
    ];
    protected $hidden = [
        'id_document_signed',
    ];

    protected $fillable = [
        'id_document_signed_qr',
        'id_document_signed',
        'identifier',
    ];
    protected function casts(): array
    {
        return [
            'id_document_signed_qr' => 'string',
            'id_document_signed' => 'string',
        ];
    }
    
}
