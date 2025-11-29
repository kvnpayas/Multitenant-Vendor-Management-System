<?php

namespace App\Providers;

use App\Repositories\InvoiceRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;

class InvoiceRepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(InvoiceRepositoryInterface::class, InvoiceRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
