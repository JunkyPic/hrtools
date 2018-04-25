<?php

namespace App\Providers;

use App\Mapping\RolesAndPermissions;
use Illuminate\Support\ServiceProvider;

class RolesAndPermissionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
      $this->app->singleton(RolesAndPermissions::class, function ($app) {
        return new RolesAndPermissions();
      });
    }
}
