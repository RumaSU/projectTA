<?php

namespace App\Models\Signatures;

use Illuminate\Database\Eloquent\Model;

class SignatureData extends Model
{
    protected $table = 'signatures_data'; // Nama tabel
    protected $primaryKey = 'id_signature_data'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = ['id_signature_data'];
    protected $guard = 'web';
    protected $hidden = [
        'id_signature',
    ];

    protected $fillable = [
        'id_signature_data',
        'id_signature',
        'pad_base64',
        'pad_points',
    ];
    protected function casts(): array
    {
        return [
            'id_signature_data' => 'string',
            'id_signature' => 'string',
        ];
    }
}
