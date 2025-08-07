<?php

namespace App\Enums;

use App\Contracts\Enums\BaseEnumInterface;
use App\Contracts\Enums\HasDefaultEnum;
use App\Trait\InteractWithBaseEnum;

use Symfony\Component\HttpFoundation\File\File;


enum HashHmac: string implements BaseEnumInterface, HasDefaultEnum {
    use InteractWithBaseEnum;
    
    
    // https://www.php.net/manual/en/function.hash-hmac-algos.php
    // last update php8.4
    // md2, md4, md5, 
    // sha1, sha224, sha256, sha384, sha512/224, sha512/256, sha512
    // ripemd128, ripemd160, ripemd256, ripemd320 
    // whirlpool 
    // tiger128,3, tiger160,3, tiger192,3,
    // tiger128,4, tiger160,4, tiger192,4
    // snefru, snefru256
    // gost, gost-crypto
    // haval128,3, haval160,3, haval192,3, haval224,3, haval256,3
    // haval128,4, haval160,4, haval192,4, haval224,4, haval256,4
    // haval128,5, haval160,5, haval192,5, haval224,5, haval256,5
    case MD2 = 'md2';
    case MD4 = 'md4';
    case MD5 = 'md5';
    case SHA1 = 'sha1';
    case SHA256 = 'sha256';
    case SHA384 = 'sha384';
    case SHA512_224 = 'sha512/224';
    case SHA512_256 = 'sha512/256';
    case SHA512 = 'sha512';
    case SHA3_224 = 'sha3-224';
    case SHA3_256 = 'sha3-256';
    case SHA3_384 = 'sha3-384';
    case SHA3_512 = 'sha3-512';
    case RIPEMD128 = 'ripemd128';
    case RIPEMD160 = 'ripemd160';
    case RIPEMD256 = 'ripemd256';
    case RIPEMD320 = 'ripemd320';
    case WHIRLPOOL = 'whirlpool';
    case TIGER128_3 = 'TIGER128,3';
    case TIGER160_3 = 'TIGER160,3';
    case TIGER192_3 = 'TIGER192,3';
    case TIGER128_4 = 'TIGER128,4';
    case TIGER160_4 = 'TIGER160,4';
    case TIGER192_4 = 'TIGER192,4';
    case SNEFRU = 'snefru';
    case SNEFRU256 = 'snefru256';
    case GOST = 'gost';
    case GOST_CRYPTO = 'gost-crypto';
    case HAVAL128_3 = 'haval128,3';
    case HAVAL160_3 = 'haval160,3';
    case HAVAL192_3 = 'haval192,3';
    case HAVAL224_3 = 'haval224,3';
    case HAVAL256_3 = 'haval256,3';
    case HAVAL128_4 = 'haval128,4';
    case HAVAL160_4 = 'haval160,4';
    case HAVAL192_4 = 'haval192,4';
    case HAVAL224_4 = 'haval224,4';
    case HAVAL256_4 = 'haval256,4';
    case HAVAL128_5 = 'haval128,5';
    case HAVAL160_5 = 'haval160,5';
    case HAVAL192_5 = 'haval192,5';
    case HAVAL224_5 = 'haval224,5';
    case HAVAL256_5 = 'haval256,5';
    
    
    public const MAX_SIZE_HASH_FILE = 8 * 1024 * 1024;
    public const STREAM_SIZE_FILE = 2 * 1024 * 1024;
    public const MAX_TOTAL_DEFAULT_STREAM = 1.5 * 1024 * 1024 * 1024;
    
    public static function get_default_case() {
        return static::SHA256;
    }
    
    public static function get_default(): string {
        return static::get_default_case()->value;
    }
    
    public static function get_default_name(): string {
        return static::get_default_case()->name;
    }
    
    public function label(): string {
        return ucfirst(strtolower($this->name));
    }
    
    public static function supported(): array {
        return array_intersect(static::get_map_value(), hash_hmac_algos());
    }
    
    public function is_supported(): bool {
        return in_array($this->value, static::supported(), true);
    }
    
    public function is_valid_file(string $filename): bool {
        return file_exists($filename) && is_file($filename) && $this->is_supported();
    }
    
    public function hash(string $data, string $key, bool $binary = false): ?string {
        return $this->is_supported()
            ? hash_hmac($this->value, $data, $key, $binary)
            : null;
    }
    
    public function hash_file(string $filename, string $key, bool $binary = false): ?string {
        if (! $this->is_valid_file($filename)) {
            return null;
        }
        
        $size = (new File($filename))->getSize();
        
        if ($size > self::MAX_TOTAL_DEFAULT_STREAM) {
            return $this->hash_file_stream_auto($filename, $key, $binary);
        }
        
        if ($size > self::MAX_SIZE_HASH_FILE) {
            return $this->hash_file_stream_auto($filename, $key, $binary, true);
        }
        
        $result = hash_hmac_file($this->value, $filename, $key, $binary);
        return $result === false
            ? null
            : $result;
    }

    private function hash_file_stream_auto(string $filename, string $key, bool $binary, bool $use_chunk = false): ?string {
        $handle = fopen($filename, 'rb');
        if (! $handle) {
            return null;
        }
        
        $ctx = hash_init($this->value, HASH_HMAC, $key);
        
        if ($use_chunk) {
            while (!feof($handle)) {
                $chunk = fread($handle, self::STREAM_SIZE_FILE);
                if ($chunk === false) {
                    fclose($handle);
                    return null;
                }
                hash_update($ctx, $chunk);
            }
        } else {
            hash_update_stream($ctx, $handle);
        }
        
        fclose($handle);
        
        return hash_final($ctx, $binary);
    }
}