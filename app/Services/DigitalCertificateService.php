<?php

namespace App\Services;

use App\Utils\LogUtils;
use App\Enums\Hash;

class DigitalCertificateService
{
    public static function get_default()
    {
        return new static;
    }

    public function generatePrivateKey(array $config, ?string $password = null)
    {
        $privateKey = openssl_pkey_new($config);
        if ($privateKey === false) {
            $this->logOpenSSLErrors();
            throw new \Exception('Gagal membuat private key: ' . openssl_error_string());
        }
        return $privateKey;
    }

    public function generateCSR(array $dn, $privateKey, array $config)
    {
        $csr = openssl_csr_new($dn, $privateKey, $config);
        if ($csr === false) {
            $this->logOpenSSLErrors();
            throw new \Exception('Gagal membuat CSR: ' . openssl_error_string());
        }
        return $csr;
    }

    public function signCertificate($csr, $privateKey, array $config, int $days = 365)
    {
        $cert = openssl_csr_sign($csr, null, $privateKey, $days, $config);
        if ($cert === false) {
            $this->logOpenSSLErrors();
            throw new \Exception('Gagal menandatangani sertifikat: ' . openssl_error_string());
        }
        return $cert;
    }

    public function exportPrivateKey($privateKey, ?string $password = null, array $config = [])
    {
        $privateKeyOut = null;
        if (!openssl_pkey_export($privateKey, $privateKeyOut, $password, $config)) {
            $this->logOpenSSLErrors();
            throw new \Exception('Gagal mengekspor private key: ' . openssl_error_string());
        }
        return $privateKeyOut;
    }

    public function exportCertificate($cert)
    {
        $certificateOut = null;
        if (!openssl_x509_export($cert, $certificateOut)) {
            $this->logOpenSSLErrors();
            throw new \Exception('Gagal mengekspor sertifikat: ' . openssl_error_string());
        }
        return $certificateOut;
    }

    public function extractCertificateMeta($cert, Hash $type = Hash::SHA256)
    {
        $certInfo = openssl_x509_parse($cert);
        $serialNumber = $certInfo['serialNumber'] ?? null;
        $validFrom = isset($certInfo['validFrom_time_t']) ? date('Y-m-d H:i:s', $certInfo['validFrom_time_t']) : null;
        $validTo = isset($certInfo['validTo_time_t']) ? date('Y-m-d H:i:s', $certInfo['validTo_time_t']) : null;
        $fingerprintType = $type->value ?? Hash::SHA256->value;
        $fingerprint = function_exists('openssl_x509_fingerprint')
            ? openssl_x509_fingerprint($cert, $fingerprintType)
            : null;

        return [
            'serial_number'    => $serialNumber,
            'fingerprint'      => $fingerprint,
            'fingerprint_type' => $fingerprintType,
            'valid_from'       => $validFrom,
            'valid_to'         => $validTo,
        ];
    }

    public function generateSelfSignedCertificateWithMeta(array $dn, ?string $password = null, Hash $type = Hash::SHA256): array
    {
        $config = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'config' => storage_path('ssl/openssl.cnf')
        ];

        $privateKey = $this->generatePrivateKey($config, $password);
        $csr = $this->generateCSR($dn, $privateKey, $config);
        $cert = $this->signCertificate($csr, $privateKey, $config, 365);

        $privateKeyOut = $this->exportPrivateKey($privateKey, $password, $config);
        $certificateOut = $this->exportCertificate($cert);
        $meta = $this->extractCertificateMeta($cert, $type);

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