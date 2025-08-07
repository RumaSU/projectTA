<?php

namespace App\Models\Files;

use App\Enums\Hash;
use Illuminate\Database\Eloquent\Model;

class DiskEntity extends Model
{
    protected $table = 'files_disk_entity'; // Nama tabel
    protected $primaryKey = 'id_file_disk_entity'; // Primary key
    protected $keyType = 'uuid';
    public $incrementing = false;
    protected $guarded = [
        'id_file_disk_entity',
        'id_file_disk',
        'owner_id',
    ];

    protected $fillable = [
        'id_file_disk_entity',
        'id_file_disk',
        'owner_id',
        'entity_type',
        'id_entity',
        'file_client_name',
        
        'hash_row',
        'hash_type'
    ];
    protected function casts(): array
    {
        return [
            'id_file_disk_entity' => 'string',
            'id_file_disk' => 'string',
            'owner_id' => 'string',
        ];
    }
    
    
    public function get_column_to_hash(): array {
        return [
            'id_file_disk_entity',
            'id_file_disk',
            'owner_id',
            'entity_type',
            'id_entity',
            'file_client_name'
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
        if ($json === false) {
            return false;
        }
        
        $check = $hashEnum->hash($json);
        
        return $hashEnum->equals($hash, $check);
    }
    
}
