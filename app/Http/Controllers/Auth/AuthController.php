<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function login(Request $request)
  {
    $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required'],
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
      return response()->json(['message' => 'Invalid credentials'], 401);
    }


    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json([
      'token' => $token,
      'user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'organization_id' => $user->organization_id,
        'organization' => $user->organization,
      ]
    ]);
  }

  public function register(Request $request)
  {
    $user = $request->user();

    if (!$user || !$user->isAdmin()) {
      return response()->json(['message' => 'Forbidden'], 403);
    }

    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'email', 'unique:users,email'],
      'password' => ['required', 'confirmed', 'string', 'min:6'],
      'role' => ['required', 'in:admin,accountant'],
    ]);

    $tenantId = $request->attributes->get('tenant_id');

    $newUser = User::create([
      'organization_id' => $tenantId,
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => $request->role,
    ]);

    return response()->json([
      'message' => 'User registered successfully',
      'user' => [
        'id' => $newUser->id,
        'name' => $newUser->name,
        'email' => $newUser->email,
        'role' => $newUser->role,
        'organization_id' => $newUser->organization_id,
      ]
    ], 201);
  }

  public function logout(Request $request)
  {
    $request->user()->tokens()->delete();

    return response()->json(['message' => 'Logged out']);
  }

  public function me(Request $request)
  {
    $user = $request->user();
    $user['organization'] = $user->organization;
    return response()->json($user);
  }
}
