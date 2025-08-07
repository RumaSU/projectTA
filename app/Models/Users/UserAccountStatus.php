<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserAccountStatus extends Model
{
    protected $table = 'users_account_status'; // Nama tabel

    protected $primaryKey = 'id_user_account_status'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $hidden = [
        'id_user',
    ];

    protected $fillable = [
        'id_user_account_status',
        'id_user',
        'type',
        'is_confirmed',
        'confirm_date',
    ];
    protected function casts(): array
    {
        return [
            'id_user_account_status' => 'string',
            'id_user' => 'string',
        ];
    }

    // Relasi terbalik ke DbUser
    public function user()
    {
        return $this->belongsToMany(\App\Models\Users\User::class, 'id_user', 'id_user', 'id_user', 'id_user');
    }
}
