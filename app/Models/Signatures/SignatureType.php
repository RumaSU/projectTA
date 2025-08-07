<?php

namespace App\Models\Signatures;

use Illuminate\Database\Eloquent\Model;

class SignatureType extends Model
{
    protected $table = 'signatures_type'; // Nama tabel
    protected $primaryKey = 'id_signature_type'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = ['id_signature_type'];
    protected $hidden = [
        'id_user',
    ];

    protected $fillable = [
        'id_signature_type',
        'id_signature',
        'type', // signature, paraf
    ];
    protected function casts(): array
    {
        return [
            'id_signature' => 'string',
            'id_user' => 'string',
        ];
    }

    
    public function signature(){
        // return $this->belongsToMany(\App\Models\Signatures\Signature::class, 'signatures_type_belongs', 'id_signature_type', 'id_signature', 'id_signature_type', 'id_signature');
        return $this->belongsTo(\App\Models\Signatures\Signature::class, 'id_signature', 'id_signature');
    }
    
    
    public function signature_file() {
        return $this->hasMany(\App\Models\Signatures\SignatureFile::class, 'id_signature_type', 'id_signature_type');
    }
    
}
