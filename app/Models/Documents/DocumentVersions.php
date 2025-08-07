<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class DocumentVersions extends Model
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
    protected $table = 'documents_versions'; // Nama tabel
    protected $primaryKey = 'id_document_version'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document_version',
        'id_document',
        'version',
    ];
    protected $hidden = [
        'id_document',
    ];
    
    protected $fillable = [
        'id_document_version',
        'id_document',
        'version',
    ];
    
    protected function casts(): array
    {
        return [
            'id_document_version' => 'string',
            'id_document' => 'string',
        ];
    }
}
