<?php

namespace App\Library;


use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;


class Helper {
    
    /**
    * Initializes and returns the host URL based on the current request.//+
    *
    * @return string The complete host URL, including the protocol, host, and port if applicable.//+
    */
    public static function initializeHostUrl() {
        return (Request::secure() ? 'https' : 'http') . '://' . Request::getHost() . (Request::getPort() ? ':' . Request::getPort() : '') . '/';
    }

    /**
    * Retrieves a list of common image file extensions.//+
    *
    * @return array An array of strings representing image file extensions.//+
    */
    public static function getImageExtension() {
        return [
            "ase","art","bmp","blp","cd5","cit","cpt","cr2","cut","dds","dib","djvu","egt","exif","gif","gpl","grf","icns","ico","iff","jng","jpeg","jpg","jfif","jp2","jps","lbm","max","miff","mng","msp","nef","nitf","ota","pbm","pc1","pc2","pc3","pcf","pcx","pdn","pgm","PI1","PI2","PI3","pict","pct","pnm","pns","ppm","psb","psd","pdd","psp","px","pxm","pxr","qfx","raw","rle","sct","sgi","rgb","int","bw","tga","tiff","tif","vtf","xbm","xcf","xpm","3dv","amf","ai","awg","cgm","cdr","cmx","dxf","e2d","egt","eps","fs","gbr","odg","svg","stl","vrml","x3d","sxd","v2d","vnd","wmf","emf","art","xar","png","webp","jxr","hdp","wdp","cur","ecw","iff","lbm","liff","nrrd","pam","pcx","pgf","sgi","rgb","rgba","bw","int","inta","sid","ras","sun","tga","heic","heif"
        ];
    }
    
    public static function generateUniqueUuId(string $uuidVer = 'v4', $column = null, $model = null){
        $randLength = rand(8, 16);
        $instance = new self();
        $dataUuid = $instance->getRamseyUuid($uuidVer, $randLength);

        if($model && $column) {
            $isUnique = false;
            while (!$isUnique) {
                if (! $model::where($column, '=', $dataUuid)->exists()) {
                    $isUnique = true;
                } else {
                    $dataUuid = $instance->getRamseyUuid($uuidVer, $randLength);
                }
            }
        }
        
        return $dataUuid;
    }
    
    public static function generateUniqueString($length = 8, $column = null, $model = null){
        $dataRandomString = Str::random($length);
        // $dataRandomString = self::randStrNum($length);

        if($model && $column) {
            $isUnique = false;
            while (!$isUnique) {
                if (! $model::where($column, '=', $dataRandomString)->exists()) {
                    $isUnique = true;
                }
                
                $dataRandomString = Str::random($length);
                // $dataRandomString = self::randStrNum($length);
            }
        }
        
        return $dataRandomString;
    }
    
    public static function checkDuplicateValue($value, $column, $model) {
        return $model::where($column, '=', $value)->exists();
    }
    
    public static function randStr(int $length, bool $withLower = true, bool $withSymbol = false) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($withLower) $characters .= 'abcdefghijklmnopqrstuvwxyz';
        if ($withSymbol) $characters.= "!@#$%^&*()-_=+[]{};:,.<>?/|~`";
        return substr(str_shuffle($characters), 0, $length);
    }
    
    public static function encryptData($keyCrypt, $data, $cipher = 'aes-256-cbc', $hashAlgo = 'sha256', $tag = '') {
        $iv = random_bytes(openssl_cipher_iv_length($cipher));
        $key = substr(hash($hashAlgo, $keyCrypt, true), 0, 32);
        
        if (gettype($data) != 'string') {
            $data = json_encode($data);
        }
        
        $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv, $tag);
        
        $encryptData = [
            'iv' => base64_encode($iv),
            'tag' => base64_encode($tag),
            'ciphertext' => $encrypted
        ];
        
        return base64_encode(json_encode($encryptData));
    }
    
    public static function decryptData($encryptedData, $key, $cipher = 'aes-256-cbc', $hashAlgo = 'sha256') {
        $key = substr(hash('sha256', $key, true), 0, 32);
        
        $data = json_decode(base64_decode($encryptedData));
        $iv = base64_decode($data->iv);
        $tag = base64_decode($data->tag);
        $ciphertext = $data->ciphertext;
    
        $decrypted = openssl_decrypt($ciphertext, $cipher, $key, 0, $iv, $tag);
    
        return json_decode($decrypted, true); // Kembalikan sebagai array/objek
    }
    
    // public static function randomString(int $length, bool $lower = true, bool $number = true, bool $symbol = false) {
    //     $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //     if ($lower) $characters .= 'abcdefghijklmnopqrstuvwxyz';
    //     if ($number) $characters .= '0123456789';
    //     if ($symbol) $characters .= '!@#$%^&*()`~';
    //     return substr(str_shuffle($characters), 0, $length);
    // }
    
    private function getRamseyUuid($ver = 'v4', int $randLength = 8) {
        switch($ver) {
            case 'v1':
                return Uuid::uuid1();
            case 'v2':
                return Uuid::uuid2(Uuid::DCE_DOMAIN_PERSON);
            case 'v3':
                return Uuid::uuid3(Uuid::NAMESPACE_URL, Str::random($randLength));
            case 'v4':
                return Uuid::uuid4();
            case 'v5':
                return Uuid::uuid5(Uuid::NAMESPACE_URL, Str::random($randLength));
            case 'v6':
                return Uuid::uuid6();
            case 'v7':
                return Uuid::uuid7();
            default:
                self::getRamseyUuid('v4', $randLength);
        }
    }
}