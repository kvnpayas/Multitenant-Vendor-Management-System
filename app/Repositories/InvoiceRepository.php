<?php

namespace App\Repositories;

use App\Models\Vendor;
use App\Models\Invoice;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;

class InvoiceRepository implements InvoiceRepositoryInterface
{
    public function paginateForTenant(int $tenantId, int $perPage = 15): LengthAwarePaginator
    {
        return Invoice::forTenant($tenantId)
            ->with('vendor')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function findForTenant(int $tenantId, int $id): ?Invoice
    {
        return Invoice::forTenant($tenantId)
            ->with('vendor')
            ->find($id);
    }

    public function createForTenant(int $tenantId, array $data): Invoice
    {
        $vendor = Vendor::find($data['vendor_id']);
        if (!$vendor || $vendor->organization_id !== $tenantId) {
            throw new \Exception('Vendor does not belong to tenant.');
        }
        $data['organization_id'] = $tenantId;
        return Invoice::create($data);
    }

    public function updateForTenant(int $tenantId, int $id, array $data): Invoice
    {
        $invoice = $this->findForTenant($tenantId, $id);
        $invoice->update($data);
        return $invoice;
    }

    public function deleteForTenant(int $tenantId, int $id): bool
    {
        $invoice = $this->findForTenant($tenantId, $id);
        return $invoice->delete();
    }
}
