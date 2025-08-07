<?php

namespace App\Models\Signatures;

use Illuminate\Database\Eloquent\Model;

class SignatureFile extends Model
{
    protected $table = 'signatures_files'; // Nama tabel
    protected $primaryKey = 'id_signature_file'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = ['id_signature_file'];
    protected $hidden = [
        'id_signature_type',
        'id_file_signature',
    ];

    protected $fillable = [
        'id_signature_file',
        'id_signature_type',
        'id_file_signature',
        
        'variant',
    ];
    protected function casts(): array
    {
        return [
            'id_signature_file' => 'string',
            'id_signature_type' => 'string',
            'id_file_signature' => 'string',
        ];
    }
    
    public function signature_type(){
        // return $this->belongsToMany(\App\Models\Signatures\Signature::class, 'signatures_type_belongs', 'id_signature_type', 'id_signature', 'id_signature_type', 'id_signature');
        return $this->belongsTo(\App\Models\Signatures\SignatureType::class, 'id_signature_type', 'id_signature_type');
    }
    
    public function file_signature(){
        // return $this->belongsToMany(\App\Models\Signatures\Signature::class, 'signatures_type_belongs', 'id_signature_type', 'id_signature', 'id_signature_type', 'id_signature');
        return $this->belongsTo(\App\Models\Files\FileSignature::class, 'id_file_signature', 'id_file_signature');
    }
    
    
}
