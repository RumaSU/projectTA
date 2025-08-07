<?php

namespace App\Models\Token;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $table = 'token_upload'; // Nama tabel

    protected $primaryKey = 'id_token_upload'; // Primary key
    protected $hidden = [
        'session_id',
    ];

    protected $fillable = [
        'session_id',
        'token',
        'token_resumable',
        'used',
        'expired_at',
    ];
    protected function casts(): array
    {
        return [
            'id_user' => 'string',
        ];
    }
    
}
