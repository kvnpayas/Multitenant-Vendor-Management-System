<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\OrganizationRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Resources\OrganizationResource;

class OrganizationController extends Controller
{
  private OrganizationRepositoryInterface $repo;

  public function __construct(OrganizationRepositoryInterface $repo)
  {
    $this->repo = $repo;
  }
  public function index(Request $request)
  {
    $tenantId = $request->attributes->get('tenant_id');
    return OrganizationResource::collection($this->repo->all($tenantId));
  }

}
