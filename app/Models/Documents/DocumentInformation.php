<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class DocumentInformation extends Model
{
    /**
     * Summary of table
     * List column
     * - id_document
     * - name
     * - name_version
     */
    protected $table = 'documents_information'; // Nama tabel
    protected $primaryKey = 'id_document'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document',
        'name_version',
    ];
    protected $hidden = [
        'id_document',
    ];

    protected $fillable = [
        'id_document',
        'name',
        'name_version',
    ];
    protected function casts(): array
    {
        return [
            'id_document' => 'string',
        ];
    }
    
}
