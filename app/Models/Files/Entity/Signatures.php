<?php

namespace App\Models\Files\Entity;

use Illuminate\Database\Eloquent\Model;

use App\Enums\Hash;

class Signatures extends Model
{
    protected $table = 'files_signatures'; // Nama tabel
    protected $primaryKey = 'id_file_signature'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_file_signature',
    ];

    protected $fillable = [
        'id_file_signature',
        'id_file_disk',
        'id_user',
        
        'type',
        'disk',
        'path',
        'file_name',
        'file_client_name',
        'extension',
        'mime_type',
        'size_byte',
        
        'hash_row',
        'hash_type'
    ];
    
    protected function casts(): array
    {
        return [
            'id_file_signature' => 'string',
            'id_file_disk' => 'string',
            'id_user' => 'string',
        ];
    }
    
    public function get_column_to_hash(): array {
        return [
            'id_file_signature',
            'id_file_disk',
            'id_user',
            'disk',
            'path',
            'file_name',
            'file_client_name',
            'extension',
            'mime_type',
            'size_byte',
        ];
    }
    
    public function is_hash_valid(): bool {
        
        $hash = $this->hash_row;
        $hash_type = $this->hash_type;
        
        $hashEnum = Hash::from_value($hash_type);
        if (! $hashEnum) {
            return false;
        }
        
        $payload = collect($this->get_column_to_hash())
            ->mapWithKeys(fn($key) => [$key => $this->{$key}])
            ->all();
        
        ksort($payload);
        
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $check = $hashEnum->hash($json);
        
        return $hashEnum->equals($hash, $check);
    }
}
