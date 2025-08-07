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
    
    public function UserData() {
        return $this->hasOne(\App\Models\Users\UserData::class, 'id_user', 'id_user')->first();
    }
    public function UserPersonal() {
        return $this->hasOne(\App\Models\Users\UserPersonal::class, 'id_user', 'id_user');
    }
    public function UserAccountStatus() {
        return $this->hasOne(\App\Models\Users\UserAccountStatus::class, 'id_user', 'id_user');
    }
    public function UserPhone() {
        return $this->hasMany(\App\Models\Users\UserPhone::class, 'id_user', 'id_user');
    }
    public function UserRememberToken() {
        return $this->hasMany(\App\Models\Users\UserRememberToken::class, 'id_user', 'id_user');
    }
    
    // Signature
    public function Signature() {
        return $this->hasMany(\App\Models\Signatures\Signature::class, 'id_user', 'id_user');
    }
    
    
}
