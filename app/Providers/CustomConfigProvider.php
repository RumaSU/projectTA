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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
