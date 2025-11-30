<?php

namespace App\Repositories\Interfaces;

use App\Models\Vendor;
use Illuminate\Pagination\LengthAwarePaginator;

interface VendorRepositoryInterface
{
    public function all(int $tenantId): LengthAwarePaginator;

    public function find(int $tenantId, int $id): ?Vendor;

    public function create(int $tenantId, array $data): Vendor;

    public function update(int $tenantId, int $id, array $data): Vendor;

    public function delete(int $tenantId, int $id): bool;
}
