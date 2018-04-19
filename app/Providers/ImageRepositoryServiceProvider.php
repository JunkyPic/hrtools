<?php

namespace App\Providers;

use App\Repository\ImageRepository;
use Illuminate\Support\ServiceProvider;

class ImageRepositoryServiceProvider extends ServiceProvider
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
        $this->app->singleton(ImageRepository::class, function ($app) {
            return new ImageRepository();
        });
    }
}
