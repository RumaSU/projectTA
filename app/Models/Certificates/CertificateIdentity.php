<?php

namespace App\Models\Certificates;

use Illuminate\Database\Eloquent\Model;

class CertificateIdentity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'certificates_identity';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_certificate_identity';
    
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
        'id_certificate_identity'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id_certificate',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $fillable = [
        'id_certificate_identity',
        'id_certificate',
        'country_name',
        'state_or_province_name',
        'locality_name',
        'organization_name',
        'organizational_unit_name',
        'common_name',
        'email_address',
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id_certificate_identity' => 'string',
        'id_certificate' => 'string',
    ];
    
    /**
     * Get the certificate associated with the identity.
     */
    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'id_certificate', 'id_certificate');
    }
}
