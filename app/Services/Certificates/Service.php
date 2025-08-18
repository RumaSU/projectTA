<?php

namespace App\Services\Certificates;

use App\Models\Certificates\Certificate;
use App\Models\Certificates\CertificateIdentity;

class Service {
    
    public static function handler() {
        return new static;
    }
    
    public function getCertificate(string $idCertificate)
    {
        return Certificate::find($idCertificate);
    }
    
    public function getCertificateBySerial(string $serialNumber)
    {
        return Certificate::where('serial_number', $serialNumber)->first();
    }
    
    
    public function getAllCertificatesUser(string $idUser)
    {
        return Certificate::where('id_user', $idUser)->get();
    }
    
    public function getCertificateUser(string $idUser)
    {
        return Certificate::where('id_user', $idUser)->first();
    }
    
    public function getLatestCertificateUser(string $idUser) {
        return Certificate::where('id_user', $idUser)
            ->orderByDesc('valid_from')
            ->first();
    }
    
    public function getCertificateIdentity(string $idCertificateIdentity)
    {
        return CertificateIdentity::find($idCertificateIdentity);
    }
    
    public function getCertificateIdentityByCertificate(string $idCertificate) {
        return CertificateIdentity::where('id_certificate', $idCertificate)->first();
    }
    
    public function getAllCertificateIdentities(string $idCertificate) {
        return CertificateIdentity::where('id_certificate', $idCertificate)->get();
    }
    
    public function getCertificateIdentityByUser(string $idUser) {
        return CertificateIdentity::whereHas('certificate', function($query) use ($idUser) {
            $query->where('id_user', $idUser);
        })->first();
    }
    
    
    public function isCertificateValid(string $idCertificate): bool {
        $certificate = $this->getCertificate($idCertificate);
        if (!$certificate) {
            return false;
        }
        
        $now = new \DateTime();
        $validFrom = new \DateTime($certificate->valid_from);
        $validTo = new \DateTime($certificate->valid_to);
        
        return $now >= $validFrom && $now <= $validTo;
    }
    
    public function isCertificateUserValid(string $idUser, bool $latest = true): bool {
        
        $certificate = $latest
            ? $this->getLatestCertificateUser($idUser)
            : $this->getCertificateUser($idUser);
        
        if (!$certificate) {
            return false;
        }
        
        $now = new \DateTime();
        $validFrom = new \DateTime($certificate->valid_from);
        $validTo = new \DateTime($certificate->valid_to);
        
        return $now >= $validFrom && $now <= $validTo;
    }
}