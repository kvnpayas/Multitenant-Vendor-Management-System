<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Organization;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorTest extends TestCase
{
    use RefreshDatabase;

    public function actingAsAdmin()
    {
        $organization = Organization::factory()->create();
        $admin = User::factory()->create([
            'role' => 'admin',
            'organization_id' => $organization->id
        ]);

        $token = $admin->createToken('token')->plainTextToken;

        return [$admin, $token, $organization];
    }

    public function test_admin_can_create_vendor()
    {
        [$user, $token] = $this->actingAsAdmin();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/vendors', [
                    'name' => 'Supplier One',
                    'contact' => '0909090909',
                ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('vendors', [
            'name' => 'Supplier One',
            'organization_id' => $user->organization_id
        ]);
    }

    public function test_accountant_cannot_create_vendor()
    {
        $organization = Organization::factory()->create();
        $acc = User::factory()->create([
            'role' => 'accountant',
            'organization_id' => $organization->id
        ]);

        $token = $acc->createToken('token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/vendors', [
                    'name' => 'Blocked Vendor'
                ]);

        $response->assertStatus(403);
    }

    public function test_tenant_cannot_access_other_tenant_vendor()
    {
        [$admin, $token, $org] = $this->actingAsAdmin();
        $organization = Organization::factory()->create();
        Vendor::factory()->create([
            'id' => 1,
            'organization_id' => $organization->id
        ]);

        // [$admin, $token] = $this->actingAsAdmin();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/vendors/' . $org->id);

        $response->assertStatus(404);
    }
}
