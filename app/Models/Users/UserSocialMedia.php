<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserSocialMedia extends Model
{
    protected $table = 'users_social_media'; // Nama tabel

    protected $primaryKey = 'id_user_social_media'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $hidden = [
        'id_user',
    ];

    protected $fillable = [
        'id_user_social_media',
        'id_user',
        'social_name',
        'social_link',
        'social_status',
    ];
    protected function casts(): array
    {
        return [
            'id_user_social_media' => 'string',
            'id_user' => 'string',
        ];
    }

    // Relasi terbalik ke DbUser
    public function user()
    {
        return $this->belongsTo(\App\Models\Users\User::class, 'id_user', 'id_user');
    }
}
