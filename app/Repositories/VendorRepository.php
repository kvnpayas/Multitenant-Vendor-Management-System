<?php

namespace App\Repositories;

use App\Models\Vendor;
use App\Repositories\Interfaces\VendorRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class VendorRepository implements VendorRepositoryInterface
{
  public function all(int $tenantId): LengthAwarePaginator
  {
    return Vendor::forTenant($tenantId)->orderBy('id', 'asc')->paginate(10);
  }
  public function getAll(int $tenantId): Collection
  {
    return Vendor::forTenant($tenantId)->get();
  }

  public function find(int $tenantId, int $id): ?Vendor
  {
    return Vendor::forTenant($tenantId)->find($id);
  }

  public function create(int $tenantId, array $data): Vendor
  {
    $data['organization_id'] = $tenantId;

    return Vendor::create($data);
  }

  public function update(int $tenantId, int $id, array $data): Vendor
  {
    $vendor = $this->find($tenantId, $id);

    if (!$vendor) {
      throw new \Exception("Vendor not found");
    }

    $vendor->update($data);

    return $vendor;
  }

  public function delete(int $tenantId, int $id): bool
  {
    $vendor = $this->find($tenantId, $id);

    if (!$vendor) {
      throw new \Exception("Vendor not found");
    }

    return $vendor->delete();
  }
}