<?php

namespace App\Providers;

use App\Repositories\VendorRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\VendorRepositoryInterface;

class VendorRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(VendorRepositoryInterface::class, VendorRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
