<?php

namespace App\Models\Certificates;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'certificates';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_certificate';
    
    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'uuid';
    
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id_certificate'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id_user',
        'private_key',
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_certificate',
        'id_user',
        'certificate',
        'private_key',
        'valid_from',
        'valid_to',
        'serial_number',
        'fingerprint',
        'fingerprint_type',
        'issuer',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected function casts(): array
    {
        return [
            'id_certificate' => 'string',
            'id_user' => 'string',
            'valid_from' => 'datetime',
            'valid_to' => 'datetime',
        ];
    }
    
    /**
     * Get the user that owns the certificate.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    
    /**
     * Get the identity information associated with the certificate.
     */
    public function identity()
    {        
        return $this->hasOne(CertificateIdentity::class, 'id_certificate', 'id_certificate');
    }
    
}
