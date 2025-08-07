<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class DocumentFile extends Model
{
    /**
     * Summary of table
     * List column
     * - id_document_file
     * - id_document_version
     * - id_file_document
     */
    protected $table = 'documents_file'; // Nama tabel
    protected $primaryKey = 'id_document_file'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document_file',
        'id_document_version',
        'id_file_document',
    ];
    protected $hidden = [
        
    ];

    protected $fillable = [
        'id_document_file',
        'id_document_version',
        'id_file_document',
    ];
    protected function casts(): array
    {
        return [
            'id_document_file' => 'string',
            'id_document_version' => 'string',
            'id_file_document' => 'string',
        ];
    }
}
