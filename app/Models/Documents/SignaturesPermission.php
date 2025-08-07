<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class SignaturesPermission extends Model
{
    /**
     * Summary of table
     * List column
     * - id_document_signature_permission
     * - id_document_signature
     * - id_document_collaborator
     * - permission
     */
    protected $table = 'documents_signatures_permission'; // Nama tabel
    protected $primaryKey = 'id_document_signature_permission'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document_signature_permission',
        'id_document_signature',
        'id_document_collaborator',
        
        'permission',
    ];
    protected $hidden = [
        'id_document_signature',
        'id_document_collaborator',
    ];

    protected $fillable = [
        'id_document_signature_permission',
        'id_document_signature',
        'id_document_collaborator',
        
        'permission',
    ];
    
    protected function casts(): array
    {
        return [
            'id_document_signature_signer' => 'string',
            'id_document_signature' => 'string',
            'id_document_collaborator' => 'string',
        ];
    }
}
