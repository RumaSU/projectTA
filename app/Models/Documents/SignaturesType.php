<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class SignaturesType extends Model
{
    /**
     * Summary of table
     * List column
     * - id_document_signature
     * - type
     * - type_changed
     */
    protected $table = 'documents_signatures_type'; // Nama tabel
    protected $primaryKey = 'id_document_signature'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document_signature',
        'type',
        'type_changed',
    ];
    protected $hidden = [
        'id_document_signature',
    ];

    protected $fillable = [
        'id_document_signature',
        'type',
        'type_changed',
    ];
    protected function casts(): array
    {
        return [
            'id_document_signature' => 'string',
        ];
    }
}
