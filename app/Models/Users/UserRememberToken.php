<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserRememberToken extends Model
{
    protected $table = 'users_remember_token'; // Nama tabel

    protected $primaryKey = 'id_user_remember_token'; // Primary key
    protected $guard = 'web';
    protected $hidden = [
        'id_user',
    ];

    protected $fillable = [
        'id_user_remember_token',
        'id_user',
        'remember_token',
        'expired_date',
    ];
    protected function casts(): array
    {
        return [
            'id_user_remember_token' => 'string',
            'id_user' => 'string',
        ];
    }

    // Relasi terbalik ke DbUser
    public function user()
    {
        return $this->belongsTo(\App\Models\Users\User::class, 'id_user', 'id_user');
    }
}
