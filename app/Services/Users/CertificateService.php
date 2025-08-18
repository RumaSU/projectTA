<?php
namespace App\Services\Users;

use App\Models\Certificates\Certificate;
use App\Models\Certificates\CertificateIdentity;

class CertificateService {
    public static function getCertificate(string $idCertificate)
    {
        return Certificate::find($idCertificate);
    }
    
    public static function getCertificateBySerial(string $serialNumber)
    {
        return Certificate::where('serial_number', $serialNumber)->first();
    }
    
    
    
    public static function getAllCertificatesUser(string $idUser)
    {
        return Certificate::where('id_user', $idUser)->get();
    }
    
    public static function getCertificateUser(string $idUser)
    {
        return Certificate::where('id_user', $idUser)->first();
    }
    
    public static function getLatestCertificateUser(string $idUser) {
        return Certificate::where('id_user', $idUser)
            ->orderByDesc('valid_from')
            ->first();
    }
    
    
    
    
    
    
    public static function getCertificateIdentity(string $idCertificateIdentity)
    {
        return CertificateIdentity::find($idCertificateIdentity);
    }
    
    public static function getCertificateIdentityByCertificate(string $idCertificate) {
        return CertificateIdentity::where('id_certificate', $idCertificate)->first();
    }
    
    public static function getAllCertificateIdentities(string $idCertificate) {
        return CertificateIdentity::where('id_certificate', $idCertificate)->get();
    }
    
    public static function getCertificateIdentityByUser(string $idUser) {
        return CertificateIdentity::whereHas('certificate', function($query) use ($idUser) {
            $query->where('id_user', $idUser);
        })->first();
    }
    
    
    public static function isCertificateValid(string $idCertificate): bool {
        $certificate = self::getCertificate($idCertificate);
        if (!$certificate) {
            return false;
        }
        
        $now = new \DateTime();
        $validFrom = new \DateTime($certificate->valid_from);
        $validTo = new \DateTime($certificate->valid_to);
        
        return $now >= $validFrom && $now <= $validTo;
    }
    
    public static function isCertificateUserValid(string $idUser): bool {
        $certificate = self::getCertificateUser($idUser);
        if (!$certificate) {
            return false;
        }
        
        $now = new \DateTime();
        $validFrom = new \DateTime($certificate->valid_from);
        $validTo = new \DateTime($certificate->valid_to);
        
        return $now >= $validFrom && $now <= $validTo;
    }
    
}