<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;


interface UserRepositoryInterface
{
  public function createForTenant(int $tenantId, array $data): User;
  public function getTenantUser(int $tenantId): Collection;
}