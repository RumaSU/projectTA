<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class Signatures extends Model
{
    /**
     * Summary of table
     * List column
     * - id_document_signature
     * - id_document
     * - is_completed
     */
    protected $table = 'documents_signatures'; // Nama tabel
    protected $primaryKey = 'id_document_signature'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document_signature',
        'id_document',
    ];
    protected $hidden = [
        'id_document',
    ];

    protected $fillable = [
        'id_document_signature',
        'id_document',
    ];
    protected function casts(): array
    {
        return [
            'id_document_signature' => 'string',
            'id_document' => 'string',
        ];
    }
}
