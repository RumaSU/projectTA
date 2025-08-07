<?php

namespace App\Models\Signatures;

use Illuminate\Database\Eloquent\Model;

class SignatureDrawings extends Model
{
    protected $table = 'signatures_drawings'; // Nama tabel
    protected $primaryKey = 'id_signature_drawing'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = ['id_signature_drawing'];
    protected $hidden = [
        'id_signature_type',
    ];

    protected $fillable = [
        'id_signature_drawing',
        'id_signature_type',
        'variant',
        'mime_type',
        'base64_data',
        'points',
    ];
    protected function casts(): array
    {
        return [
            'id_signature_drawing' => 'string',
            'id_signature_type' => 'string',
            'points' => 'json'
        ];
    }
    
    public function signature_type() {
        $this->belongsTo(\App\Models\Signatures\SignatureType::class, 'id_signature_type', 'id_signature_type');
    }
    
    // public function signature(){
    //     // return $this->belongsToMany(
    //     //     \App\Models\Signatures\Signature::class, 
    //     //     'signatures_data_belongs', 
    //     //     'id_signature_data', 
    //     //     'id_signature', 
    //     //     'id_signature_data',
    //     //     'id_signature', 
    //     // );
    //     return $this->belongsTo(\App\Models\Signatures\Signature::class, 'id_signature', 'id_signature');
    // }
}
