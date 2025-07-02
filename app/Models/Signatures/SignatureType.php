<?php

namespace App\Models\Signatures;

use Illuminate\Database\Eloquent\Model;

class SignatureType extends Model
{
    protected $table = 'signatures_type'; // Nama tabel
    protected $primaryKey = 'id_signature_type'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = ['id_signature_type'];
    protected $guard = 'web';
    protected $hidden = [
        'id_user',
    ];

    protected $fillable = [
        'id_signature_type',
        'id_signature',
        'type',
        'default',
    ];
    protected function casts(): array
    {
        return [
            'id_signature' => 'string',
            'id_user' => 'string',
        ];
    }

    // Relasi terbalik ke DbUser
    public function user()
    {
        return $this->belongsTo(\App\Models\Users\User::class, 'id_user', 'id_user');
    }
}
