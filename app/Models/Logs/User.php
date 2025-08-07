<?php

namespace App\Models\Logs;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'logs_user';

    protected $primaryKey = 'id';
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id',
        'id_user',
        'identifier',
    ];
    
    protected $fillable = [
        'id',
        'id_user',
        'identifier',
        
        'title_log',
        'desc_log',
        'type',
        'action',
        'actor',
        
        /**
         * "label",
         * "field",
         * "list": {
         *    "label"
         *    "field"
         *    "old"
         *    "new" }
         * "old",
         * "new",
         */
        'payload',
        'ip_address',
        'user_agent',
    ];
    
    protected $hidden = [
        'id_user',
        'identifier',
    ];
    
    protected function casts(): array
    {
        return [
            'id' => 'string',
            'id_user' => 'string',
            'identifier' => 'string',
            'payload' => 'json',
        ];
    }
}
