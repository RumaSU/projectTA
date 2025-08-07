<?php

namespace App\Enums\Config;

use App\Contracts\Enums\BaseEnumInterface;
use App\Contracts\Enums\HasDefaultEnum;

use App\Trait\InteractWithBaseEnum;

use App\Utils\Config\Filesystem as FilesystemConfig;

enum Filesystem: string implements BaseEnumInterface, HasDefaultEnum{
    use InteractWithBaseEnum;
    
    case LOCAL = 'local';
    case public = 'public';
    case S3 = 's3';
    case SIGNATURES = 'signatures';
    case DOCUMENTS = 'documents';
    
    public static function get_default(): string {
        return static::from_value(FilesystemConfig::getConfigDefault())->value;
    }
    
    public static function get_default_name(): string {
        return static::from_value(FilesystemConfig::getConfigDefault())->name;
    }
    
    public static function get_cloud_disks(): array {
        return [
            static::S3->value
        ];
    }
    
    public function label(): string {
        return ucfirst(strtolower($this->name));
    }
    
    public function is_cloud(): bool {
        return in_array($this->value, static::get_cloud_disks());
    }
    
    public function is_visible(): bool {
        return $this->get_config_visibility() === 'public';
    }
    
    public function is_served(): bool {
        return $this->get_config_serve();
    }
    
    public function get_config(): ?array {
        return FilesystemConfig::getConfigDisks()[$this->value] ?? null;
    }
    
    public function get_config_driver() {
        return $this->get_config()['driver'];
    }
    
    public function get_config_serve(): bool {
        return $this->get_config()['serve'] ?? false;
    }
    
    public function get_config_throw(): bool {
        return $this->get_config()['throw'] ?? false;
    }
    
    public function get_config_report(): bool {
        return $this->get_config()['report'] ?? false;
    }
    
    public function get_config_url(): ?string {
        return $this->get_config()['url'] ?? null;
    }
    
    public function get_config_visibility(): ?string {
        return $this->get_config()['visibility'] ?? null;
    }
    
}