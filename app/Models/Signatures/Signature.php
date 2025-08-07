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
    protected $hidden = [
        'id_user',
    ];

    protected $fillable = [
        'id_signature',
        'id_user',
        'is_default',
    ];
    protected function casts(): array
    {
        return [
            'id_signature' => 'string',
            'id_user' => 'string',
        ];
    }
    
    
    public function user(){
        // return $this->belongsToMany(\App\Models\Users\User::class, 'signatures_user_belongs', );
        return $this->belongsTo(\App\Models\Users\User::class, 'id_user', 'id_user');
    }
    
    public function signature_type() {
        return $this->hasMany(\App\Models\Signatures\SignatureType::class, 'id_signature', 'id_signature');
    }
    
    // public function signature_data() {
    //     return $this->hasMany(\App\Models\Signatures\SignatureData::class, 'id_signature', 'id_signature');
    // }
    
    public function signature_file() {
        return $this->hasMany(\App\Models\Signatures\SignatureFile::class, 'id_signature', 'id_signature');
    }
}
