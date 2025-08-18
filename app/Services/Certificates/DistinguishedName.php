<?php

namespace App\Services\Certificates;

class DistinguishedName {
    
    
    public string $countryName;
    public string $stateOrProvinceName;
    public string $localityName;
    public string $organizationName;
    public string $organizationalUnitName;
    public string $commonName;
    public string $emailAddress;
    
    
    public function __construct(
        string $countryName,
        string $stateOrProvinceName,
        string $localityName,
        string $organizationName,
        string $organizationalUnitName,
        string $commonName,
        string $emailAddress
    ) {
        $this->countryName = $countryName;
        $this->stateOrProvinceName = $stateOrProvinceName;
        $this->localityName = $localityName;
        $this->organizationName = $organizationName;
        $this->organizationalUnitName = $organizationalUnitName;
        $this->commonName = $commonName;
        $this->emailAddress = $emailAddress;
    }
    
    public function toArray(): array
    {
        return [
            "countryName" => $this->countryName,
            "stateOrProvinceName" => $this->stateOrProvinceName,
            "localityName" => $this->localityName,
            "organizationName" => $this->organizationName,
            "organizationalUnitName" => $this->organizationalUnitName,
            "commonName" => $this->commonName,
            "emailAddress" => $this->emailAddress,
        ];
    }
    
}