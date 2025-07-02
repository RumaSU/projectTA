<?php

namespace App\Models\Signatures;

use Illuminate\Database\Eloquent\Model;

class SignatureFile extends Model
{
    protected $table = 'signatures_file'; // Nama tabel
    protected $primaryKey = 'id_signature_file'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = ['id_signature'];
    protected $guard = 'web';
    protected $hidden = [
        'id_signature',
    ];

    protected $fillable = [
        'id_signature_file',
        'id_signature_type',
        'id_file_signature',
    ];
    protected function casts(): array
    {
        return [
            'id_signature_file' => 'string',
            'id_signature_type' => 'string',
            'id_file_signature' => 'string',
        ];
    }
}
