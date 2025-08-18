<?php

namespace App\Jobs\Observer\Users;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

use App\Models\Users\User;

use App\Utils\LogUtils;
use App\Utils\StorageUtils;
use App\Utils\ModelUtils;

use App\Services\DigitalCertificateService;
use App\Services\Certificates\Generate;
use App\Services\Certificates\DistinguishedName;

class CreateCertificate implements ShouldQueue
{
    use Queueable;
    
    protected User $user;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dn = new DistinguishedName(
            "ID",
            "Jawa Barat",
            "Indramayu",
            "Tugas Akhir - Sistem Tanda Tangan Digital",
            "Program Studi Teknik Informatika",
            $this->user->UserPersonal->fullname,
            $this->user->email
        );
        
        $certificate = new Generate($dn);
        $fingerprintType = \App\Enums\Hash::SHA256;
        $generate = $certificate->generateSelfSignedCertificate(365, $fingerprintType);
        
        $id_certificate = ModelUtils::generateNewUuid(\App\Models\Certificates\Certificate::class);
        
        $private_key = Crypt::encryptString($generate['private_key']);
        ModelUtils::create(
            \App\Models\Certificates\Certificate::class,
            [
                'id_certificate' => $id_certificate,
                'id_user' => $this->user->id_user,
                'certificate' => $generate['certificate'],
                'private_key' => $private_key,
                'serial_number' => $generate['serial_number'],
                'fingerprint' => $generate['fingerprint'],
                'fingerprint_type' => $fingerprintType->value,
                'valid_from' => $generate['valid_from'],
                'valid_to' => $generate['valid_to'],
                'issuer' => 'self-signed'
            ]
        );
        
        $id_certificate_identity = ModelUtils::generateNewUuid(\App\Models\Certificates\CertificateIdentity::class);
        $identity = $dn->toArray();
        ModelUtils::create(
            \App\Models\Certificates\CertificateIdentity::class,
            [
                'id_certificate_identity' => $id_certificate_identity,
                'id_certificate' => $id_certificate,
                'country_name' => $identity['countryName'],
                'state_or_province_name' => $identity['stateOrProvinceName'],
                'locality_name' => $identity['localityName'],
                'organization_name' => $identity['organizationName'],
                'organizational_unit_name' => $identity['organizationalUnitName'],
                'common_name' => $identity['commonName'],
                'email_address' => $identity['emailAddress'],
            ]
        );
        
        LogUtils::log('single', 'success create certificate', [
            'id_user' => $this->user->id_user,
            'id_certificate' => $id_certificate,
        ]);
    }
}
