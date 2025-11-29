<?php

namespace App\Repositories\Interfaces;

use App\Models\Invoice;
use Illuminate\Pagination\LengthAwarePaginator;

interface InvoiceRepositoryInterface
{
    public function paginateForTenant(int $tenantId, int $perPage = 15): LengthAwarePaginator;
    public function findForTenant(int $tenantId, int $id): ?Invoice;
    public function createForTenant(int $tenantId, array $data): Invoice;
    public function updateForTenant(int $tenantId, int $id, array $data): Invoice;
    public function deleteForTenant(int $tenantId, int $id): bool;
}
