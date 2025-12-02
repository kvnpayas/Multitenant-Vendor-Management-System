<?php

namespace Tests\Feature;

use App\Models\Organization;
use Tests\TestCase;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_tenant_data_is_accessible()
    {
        $organization = Organization::factory()->create();
        $organization2 = Organization::factory()->create();
        $user = User::factory()->create(['organization_id' => $organization->id]);
        $token = $user->createToken('token')->plainTextToken;

        Vendor::factory()->create(['id' => 1, 'organization_id' => $organization->id]);
        Vendor::factory()->create(['id' => 2, 'organization_id' => $organization2->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/vendors');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json('data'));
    }
}
