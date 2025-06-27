<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    // use HasFactory, Notifiable;
    use HasFactory;
    
    protected $table = 'users';

    protected $primaryKey = 'id_user';
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = ['user_id'];
    protected $guard = 'web';
    
    protected $fillable = [
        'id_user',
        'email',
        'username',
        'password',
    ];
    
    protected $hidden = [
        'password',
    ];
    
    protected function casts(): array
    {
        return [
            'id_user' => 'string',
            // 'email_verified_at' => 'datetime',
            // 'password' => 'hashed',
        ];
    }
}
