<?php

namespace App\Models\Files;

use Illuminate\Database\Eloquent\Model;

class FilesDocuments extends Model
{
    protected $table = 'files_document'; // Nama tabel
    protected $primaryKey = 'id_file_document'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_file_document',
    ];
    protected $hidden = [
        'owner_id',
    ];

    protected $fillable = [
        'id_file_document',
        'owner_id',
        'file_name',
        'file_ext',
        'file_path',
        'file_mime',
        'file_size',
    ];
    protected function casts(): array
    {
        return [
            'id_file_signature' => 'string',
            'owner_id' => 'string',
        ];
    }
}
