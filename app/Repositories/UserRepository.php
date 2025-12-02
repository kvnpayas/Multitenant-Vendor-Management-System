<?php

namespace App\Repositories;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
  public function createForTenant(int $tenantId, array $data): User
  {
    return User::create([
      'organization_id' => $tenantId,
      'name' => $data['name'],
      'email' => $data['email'],
      'role' => $data['role'],
      'password' => Hash::make($data['password']),
    ]);
  }

  public function getTenantUser(int $tenantId): Collection
  {
    return User::where('organization_id', $tenantId)->get();
  }
  
}
