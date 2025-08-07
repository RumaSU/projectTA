<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserPersonal extends Model
{
    protected $table = 'users_personal'; // Nama tabel

    // protected $primaryKey = 'id_user'; // Primary key
    protected $hidden = [
        'id_user',
    ];

    protected $fillable = [
        'id_user',
        'fullname',
        'gender',
        'birthdate',
    ];
    protected function casts(): array
    {
        return [
            'id_user' => 'string',
        ];
    }

    // Relasi terbalik ke DbUser
    public function user()
    {
        return $this->belongsTo(\App\Models\Users\User::class, 'id_user', 'id_user');
    }
}
