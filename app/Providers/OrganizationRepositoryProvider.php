<?php

namespace App\Providers;

use App\Repositories\Interfaces\OrganizationRepositoryInterface;
use App\Repositories\OrganizationRepository;
use Illuminate\Support\ServiceProvider;

class OrganizationRepositoryProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    $this->app->bind(OrganizationRepositoryInterface::class, OrganizationRepository::class);
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    //
  }
}
