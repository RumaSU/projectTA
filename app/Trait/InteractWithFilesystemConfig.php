<?php

namespace App\Trait;

trait InteractWithFilesystemConfig {
    
    protected static $CONFIG = [];
    protected static $CONFIG_PATH = 'filesystems';
    protected static $DEFAULT_MAX_FILE_SIZE = 10 * 1024 * 1024;
    
    /**
     * Loads the filesystem configuration if not already loaded.
     *
     * @return void
     */
    private static function load() {
        if (! empty(static::$CONFIG)) {
            return;
        }
        
        static::$CONFIG = config(static::$CONFIG_PATH);
    }
    
    /**
     * Get the configured disk settings.
     *
     * @return array The array of disk configurations.
     */
    public static function getConfigDisks(): array {
        static::load();
        
        return static::$CONFIG['disks'];
    }
    
    /**
     * Get the default disk name from configuration.
     *
     * @return string The default disk name.
     */
    public static function getConfigDefault(): string {
        static::load();
        
        return static::$CONFIG['default'];
    }
    
    /**
     * Get the configured symbolic links.
     *
     * @return array The array of symbolic link configurations.
     */
    public static function getConfigLinks() {
        static::load();
        
        return static::$CONFIG['links'];
    }
    
    /**
     * Check if a given disk exists in the configuration.
     *
     * @param string $disk The name of the disk to check.
     * @return bool True if the disk exists, false otherwise.
     */
    public static function checkDisk(string $disk): bool {
        $disks = static::getConfigDisks();
        
        return array_key_exists($disk, $disks);
    }
    
}