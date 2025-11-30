<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function createForTenant(int $tenantId, array $data): User;
}
