<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class DocumentPublicity extends Model
{
    /**
     * Summary of table
     * List column
     * - id_document
     * - status_changed {public, private}
     * - status_publicity
     */
    protected $table = 'documents_publicity'; // Nama tabel
    protected $primaryKey = 'id_document'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_document',
        'status_changed',
    ];
    protected $hidden = [
        'id_document',
    ];
    protected $fillable = [
        'id_document',
        'status_publicity',
        'status_changed',
    ];
    protected function casts(): array
    {
        return [
            'id_document' => 'string',
        ];
    }
}
