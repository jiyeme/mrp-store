<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LibServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        foreach(glob(app_path('Helpers') . '/*_auto.class.php') as $file) {
            require_once $file;
        }
    }
}
