<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\InvoiceResource;
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
}
