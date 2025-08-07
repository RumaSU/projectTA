<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserPhone extends Model
{
    protected $table = 'users_phone'; // Nama tabel

    protected $primaryKey = 'id_user_phone'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $hidden = [
        'id_user',
    ];

    protected $fillable = [
        'id_user_phone',
        'id_user',
        'phone_number',
        'phone_type',
    ];
    protected function casts(): array
    {
        return [
            'id_user_phone' => 'string',
            'id_user' => 'string',
        ];
    }

    // Relasi terbalik ke DbUser
    public function user()
    {
        return $this->belongsTo(\App\Models\Users\User::class, 'id_user', 'id_user');
    }
}
