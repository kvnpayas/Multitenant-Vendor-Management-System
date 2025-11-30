<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\InvoiceResource;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Repositories\Interfaces\InvoiceRepositoryInterface;

class InvoiceController extends Controller
{
    private InvoiceRepositoryInterface $invoiceRepo;

    public function __construct(InvoiceRepositoryInterface $invoiceRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
    }

    public function index(Request $request)
    {
        $tenantId = $request->attributes->get('tenant_id');
        $perPage = (int) $request->query('per_page', 15);

        $paginator = $this->invoiceRepo->paginateForTenant($tenantId, $perPage);

        return InvoiceResource::collection($paginator);
    }

    public function store(StoreInvoiceRequest $request)
    {

        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $tenantId = $request->attributes->get('tenant_id');

        $invoice = $this->invoiceRepo->createForTenant($tenantId, $request->validated());

        return new InvoiceResource($invoice->load('vendor'));
    }

    public function show(Request $request, $id)
    {
        $tenantId = $request->attributes->get('tenant_id');

        $invoice = $this->invoiceRepo->findForTenant($tenantId, $id);
        if (!$invoice) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return new InvoiceResource($invoice->load('vendor'));
    }

    public function update(UpdateInvoiceRequest $request, $id)
    {

        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $tenantId = $request->attributes->get('tenant_id');

        $invoice = $this->invoiceRepo->findForTenant($tenantId, $id);
        if (!$invoice) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $updated = $this->invoiceRepo->updateForTenant($tenantId, $id, $request->validated());

        return new InvoiceResource($updated->load('vendor'));
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $tenantId = $request->attributes->get('tenant_id');

        $invoice = $this->invoiceRepo->findForTenant($tenantId, $id);
        if (!$invoice) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $this->invoiceRepo->deleteForTenant($tenantId, $id);

        return response()->noContent();
    }
}
