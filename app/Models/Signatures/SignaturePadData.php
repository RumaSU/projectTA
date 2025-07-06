<?php

namespace App\Models\Signatures;

use Illuminate\Database\Eloquent\Model;

class SignaturePadData extends Model
{
    protected $table = 'signatures_pad_data'; // Nama tabel
    protected $primaryKey = 'id_signature_pad_data'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = ['id_signature_pad_data'];
    protected $guard = 'web';
    protected $hidden = [
        'id_signature_type',
    ];

    protected $fillable = [
        'id_signature_pad_data',
        'id_signature_type',
        'pad_key',
        'pad_mime',
        'pad_base64',
        'pad_points',
    ];
    protected function casts(): array
    {
        return [
            'id_signature_pad_data' => 'string',
            'id_signature_type' => 'string',
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
