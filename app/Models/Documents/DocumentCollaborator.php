<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class DocumentCollaborator extends Model
{
    /**
     * Summary of table
     * List column
     * - id_document_collaborator
     * - id_document
     * - id_user
     * - role {viewer, signer}
     * - role_changed
     */
    protected $table = 'documents_collaborator'; // Nama tabel
    protected $primaryKey = 'id_document_collaborator'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document_collaborator',
        'id_document',
        'role',
        'role_changed',
    ];
    protected $hidden = [
        'id_document',
    ];

    protected $fillable = [
        'id_document_collaborator',
        'id_user',
        'id_document',
        'role',
        'role_changed',
    ];
    
    protected function casts(): array
    {
        return [
            'id_document_collaborator' => 'string',
            'id_document' => 'string',
            'id_user' => 'string',
        ];
    }
}
