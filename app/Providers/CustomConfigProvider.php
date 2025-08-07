<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CustomConfigProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            config_path('custom/register_form.php'), 'custom_register_form',
        );
        $this->mergeConfigFrom(
            config_path('custom/form.php'), 'custom_form',
        );
        $this->mergeConfigFrom(
            config_path('custom/upload_file.php'), 'custom_upload',
        );
        $this->mergeConfigFrom(
            config_path('custom/document.php'), 'config_document',
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
