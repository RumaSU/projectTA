<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /**
     * Summary of table
     * List column
     * - id_document
     * - owner_id
     */
    protected $table = 'documents'; // Nama tabel
    protected $primaryKey = 'id_document'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document'
    ];
    protected $hidden = [
        'owner_id',
    ];

    protected $fillable = [
        'id_document',
        'owner_id',
        'is_delete'
    ];
    protected function casts(): array
    {
        return [
            'id_document' => 'string',
            'owner_id' => 'string',
        ];
    }
    
}
