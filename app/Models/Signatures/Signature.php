<?php

namespace App\Models\Signatures;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    protected $table = 'signatures'; // Nama tabel
    protected $primaryKey = 'id_signature'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = ['id_signature'];
    protected $guard = 'web';
    protected $hidden = [
        'id_user',
    ];

    protected $fillable = [
        'id_signature',
        'id_user',
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
