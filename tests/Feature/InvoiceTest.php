<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Invoice;
use App\Models\Organization;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTest extends TestCase
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

    public function test_admin_can_create_invoice()
    {
        [$admin, $token, $org] = $this->actingAsAdmin();

        $vendor = Vendor::factory()->create([
            'organization_id' => $org->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/invoices', [
                    'vendor_id' => $vendor->id,
                    'amount' => 1200,
                    'status' => 'pending'
                ]);

        $response->assertStatus(201);
    }

    public function test_cannot_create_invoice_with_foreign_tenant_vendor()
    {
        [$admin, $token, $org] = $this->actingAsAdmin();

        $organization = Organization::factory()->create();
        $vendor = Vendor::factory()->create([
            'organization_id' => $organization->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/invoices', [
                    'vendor_id' => $vendor->id,
                    'amount' => 1000,
                    'status' => 'pending'
                ]);

        $response->assertStatus(422);
    }

    public function test_accountant_can_only_update_status()
    {
        $organization = Organization::factory()->create();
        $acc = User::factory()->create([
            'role' => 'accountant',
            'organization_id' => $organization->id
        ]);

        $token = $acc->createToken('token')->plainTextToken;

        $invoice = Invoice::factory()->create([
            'organization_id' => $organization->id,
            'status' => 'pending'
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->putJson("/api/invoices/{$invoice->id}", [
                    'status' => 'paid'
                ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'paid'
        ]);
    }
}
