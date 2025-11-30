<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\VendorResource;
use App\Http\Requests\StoreVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Repositories\Interfaces\VendorRepositoryInterface;

class VendorController extends Controller
{
    protected $vendors;

    public function __construct(VendorRepositoryInterface $vendors)
    {
        $this->vendors = $vendors;
    }

    public function index(Request $request)
    {
        $tenantId = $request->attributes->get('tenant_id');
        return VendorResource::collection($this->vendors->all($tenantId));
    }

    public function store(StoreVendorRequest $request)
    {
        $tenantId = $request->attributes->get('tenant_id');
        $vendor = $this->vendors->create($tenantId, $request->validated());
        return new VendorResource($vendor);
    }

    public function show(Request $request, $id)
    {
        $tenantId = $request->attributes->get('tenant_id');
        $vendor = $this->vendors->find($tenantId, $id);

        if (!$vendor) {
            return response()->json(['message' => 'Vendor not found'], 404);
        }

        return new VendorResource($vendor);
    }

    public function update(UpdateVendorRequest $request, $id)
    {
        $tenantId = $request->attributes->get('tenant_id');
        $vendor = $this->vendors->update($tenantId, $id, $request->validated());

        return new VendorResource($vendor);
    }

    public function destroy(Request $request, $id)
    {
        $tenantId = $request->attributes->get('tenant_id');
        $this->vendors->delete($tenantId, $id);

        return response()->json(['message' => 'Vendor deleted']);
    }
}
