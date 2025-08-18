<?php

namespace App\Services\Certificates;

use App\Enums\Hash;

use App\Utils\LogUtils;

use App\Services\Certificates\DistinguishedName;
use App\Services\Support\UniqueValueGenerator;
use App\Utils\ModelUtils;

class Generate {
    
    
    protected array $config;
    
    protected DistinguishedName $dn;
    protected ?string $password = null;
    protected $private_key;
    protected $csr;
    protected $cert;
    protected $fingerprint;
    protected Hash $fingerprint_type;
    
    public function __construct(DistinguishedName $dn, ?string $password = null) {
        $this->dn = $dn;
        $this->password = $password;
        
        $this->config = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'config' => storage_path('ssl/openssl.cnf')
        ];
    }
    
    public function privateKey() {
        if (isset($this->private_key)) {
            return $this->private_key;
        }
        
        $generate = openssl_pkey_new($this->config);        
        if ($generate === false) {
            LogUtils::log('single', 'error openssl', [openssl_error_string()]);
            throw new \Exception('Gagal membuat private key: ' . openssl_error_string());
        }
        
        return $this->private_key = $generate;
    }
    
    public function csr() {
        if (! isset($this->private_key)) {
            $this->privateKey();
        }
        
        if (isset($this->csr)) {
            return $this->csr;
        }
        
        $generate = openssl_csr_new($this->dn->toArray(), $this->private_key, $this->config);
        if ($generate === false) {
            LogUtils::log('single', 'error openssl', [openssl_error_string()]);
            throw new \Exception('Failed to create CSR: ' . openssl_error_string());
        }

        
        return $this->csr = $generate;
    }
    
    public function cert(int $days = 365) {
        if (! isset($this->csr)) {
            $this->csr();
        }
        
        if (isset($this->cert)) {
            return $this->cert;
        }
        
        $modelCertificate = ModelUtils::createInstanceModel(\App\Models\Certificates\Certificate::class);
        $serial = UniqueValueGenerator::number(1, 'serial_number', $modelCertificate->getTable(), $modelCertificate->getConnectionName());
        
        $generate = openssl_csr_sign(
            $this->csr, 
            null, 
            $this->private_key, 
            $days, 
            $this->config,
            $serial
        );
        if ($generate === false) {
            LogUtils::log('single', 'error openssl', [openssl_error_string()]);
            throw new \Exception('Failed to sign certificate: ' . openssl_error_string());
        }
        
        return $this->cert = $generate;
    }
    
    public function fingerprint(Hash $type = Hash::SHA256){
        if (! isset($this->cert)) {
            $this->cert();
        }
        
        if (isset($this->fingerprint)) {
            return $this->fingerprint;
        }
        
        
        if (! function_exists('openssl_x509_fingerprint')) {
            LogUtils::log('single', 'error openssl', ['openssl_x509_fingerprint function is not available']);
            return;
        }
        
        $generate = openssl_x509_fingerprint($this->cert, $type->value);
        
        if ($generate) {
            $this->fingerprint_type = $type;
            return $this->fingerprint = $generate;
        }
    }
    
    public function exportPrivateKey(): string {
        if (! isset($this->private_key)) {
            $this->privateKey();
        }
        
        $out = null;
        if (! openssl_pkey_export($this->private_key, $out, $this->password, $this->config)) {
            LogUtils::log('single', 'error openssl', [openssl_error_string()]);
            throw new \Exception('Failed to export private key: ' . openssl_error_string());
        }
        
        return $out;
    }
    
    public function exportCertificate(int $days = 365): string {
        if (! isset($this->cert)) {
            $this->cert($days);
        }
        
        $out = null;
        if (! openssl_x509_export($this->cert, $out)) {
            LogUtils::log('single', 'error openssl', [openssl_error_string()]);
            throw new \Exception('Failed to export certificate: ' . openssl_error_string());
        }
        
        return $out;
    }
    
    
    public function extractCertificateMeta(int $days = 365, Hash $type = Hash::SHA256): array {
        if (! isset($this->cert)) {
            $this->cert($days);
        }
        if (! isset($this->fingerprint)) {
            $this->fingerprint($type);
        }
        
        $info = openssl_x509_parse($this->cert);
        
        $serialNumber = $info['serialNumber'] ?? null;
        $validFrom = isset($info['validFrom_time_t']) ? date('Y-m-d H:i:s', $info['validFrom_time_t']) : null;
        $validTo = isset($info['validTo_time_t']) ? date('Y-m-d H:i:s', $info['validTo_time_t']) : null;
        $fingerprint = $this->fingerprint ?? null;
        $fingerprintType = $this->fingerprint_type->value ?? null;
        
        return [
            'serial_number' => $serialNumber,
            'fingerprint' => $fingerprint,
            'fingerprint_type' => $fingerprintType,
            'valid_from' => $validFrom,
            'valid_to' => $validTo,
        ];
    }
    
    public function generateSelfSignedCertificate(int $days = 365, Hash $type = Hash::SHA256): array
    {
        $privateKeyOut = $this->exportPrivateKey();
        $certificateOut = $this->exportCertificate($days);
        $meta = $this->extractCertificateMeta($days, $type);
        
        return array_merge([
            'private_key' => $privateKeyOut,
            'certificate' => $certificateOut,
        ], $meta);
    }
    
    

    private function logOpenSSLErrors()
    {
        while ($msg = openssl_error_string()) {
            LogUtils::log('single', 'error openssl', [$msg]);
        }
    }
    
}