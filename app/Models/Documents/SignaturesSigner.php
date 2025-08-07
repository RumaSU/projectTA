<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class SignaturesSigner extends Model
{
    /**
     * Summary of table
     * List column
     * - id_document_signature_signer
     * - id_document_signature
     * - id_document_collaborator
     * - id_signature_type
     * - signing_metadata
     * - signed_at
     */
    protected $table = 'documents_signatures_signer'; // Nama tabel
    protected $primaryKey = 'id_document_signature_signer'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document_signature_signer',
        'id_document_signature',
        'id_document_collaborator',
        
        'id_signature_type',
        'signed_at',
    ];
    protected $hidden = [
        'id_document_signature',
        'id_document_collaborator',
    ];

    protected $fillable = [
        'id_document_signature_signer',
        'id_document_signature',
        'id_document_collaborator',
        'id_signature_type',
        
        'signing_metadata',
        'signed_at',
    ];
    protected function casts(): array
    {
        return [
            'id_document_signature_signer' => 'string',
            'id_document_signature' => 'string',
            'id_document_collaborator' => 'string',
            'id_signature_type' => 'string',
            'signing_metadata' => 'json', 
        ];
    }
}
