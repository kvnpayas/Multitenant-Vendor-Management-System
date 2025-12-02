<?php

namespace App\Repositories;

use App\Models\Organization;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\OrganizationRepositoryInterface;

class OrganizationRepository implements OrganizationRepositoryInterface
{
  public function all(int $tenantId): LengthAwarePaginator
  {
    return Organization::paginate(10);
  }
}
