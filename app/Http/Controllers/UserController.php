<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    private UserRepositoryInterface $repo;

    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function store(StoreUserRequest $request)
    {
        $tenantId = $request->attributes->get('tenant_id');

        $user = $this->repo->createForTenant($tenantId, $request->validated());

        return new UserResource($user);
    }
}
