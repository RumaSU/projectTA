<?php

namespace App\Models\Files;

use Illuminate\Database\Eloquent\Model;

class FileSignature extends Model
{
    protected $table = 'files_signature'; // Nama tabel
    protected $primaryKey = 'id_file_signature'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = ['id_file_signature'];
    protected $guard = 'web';
    protected $hidden = [
        'id_user',
    ];

    protected $fillable = [
        'id_file_signature',
        'id_user',
        'type',
        'file_name',
        'file_ext',
        'file_path',
        'file_type',
        'file_size',
    ];
    protected function casts(): array
    {
        return [
            'id_file_signature' => 'string',
            'id_user' => 'string',
        ];
    }
}
