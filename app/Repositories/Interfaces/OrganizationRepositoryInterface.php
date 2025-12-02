<?php

namespace App\Repositories\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface OrganizationRepositoryInterface
{
  public function all(int $tenantId): LengthAwarePaginator;
}
