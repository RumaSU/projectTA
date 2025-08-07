<?php

namespace App\Models\Files;

use Illuminate\Database\Eloquent\Model;

use App\Utils\StorageUtils;

use App\Enums\Hash;

class Disk extends Model
{
    protected $table = 'files_disk'; // Nama tabel
    protected $primaryKey = 'id_file_disk'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_file_disk',
    ];

    protected $fillable = [
        'id_file_disk',
        'disk',
        'path',
        'file_name',
        'extension',
        'mime_type',
        'size_byte',
        
        'hash_row',
        'hash_file',
        'hash_type',
        
        'status',
        'last_check'
    ];
    
    protected function casts(): array
    {
        return [
            'id_file_disk' => 'string',
        ];
    }
    
    
    public function get_column_to_hash(): array {
        return [
            'id_file_disk',
            'disk',
            'path',
            'file_name',
            'extension',
            'mime_type',
            'size_byte',
        ];
    }
    
    public function is_hash_valid(): bool {
        $hash = $this->hash_row;
        $hash_type = $this->hash_type;
         
        $hashEnum = Hash::from_value($hash_type) ?? Hash::get_default_case();
        if (! $hashEnum) {
            return false;
        }
        
        $payload = collect($this->get_column_to_hash())
            ->mapWithKeys(fn($key) => [$key => $this->{$key}])
            ->all();
        
        ksort($payload);
        
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            return false;
        }
        
        $check = $hashEnum->hash($json);
        
        return $hashEnum->equals($hash, $check);
    }
    
    public function is_hash_file_valid(): bool {
        $hash = $this->hash_file;
        $hash_type = $this->hash_type;
        
        $hashEnum = Hash::from_value($hash_type);
        if (! $hashEnum) {
            return false;
        }
        
        
        $fullpath = StorageUtils::resolvePath($this->disk, $this->path);
        if (! $fullpath) {
            return false;
        }
        
        $check = $hashEnum->hash_file($fullpath);
        
        return $hashEnum->equals($hash, $check);
    }
}
