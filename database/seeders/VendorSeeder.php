<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vendor::create([
            'organization_id' => 1,
            'name' => 'Vendor 1 Tenant 1',
        ]);

        Vendor::create([
            'organization_id' => 1,
            'name' => 'Vendor 2 Tenant 1',
        ]);

        Vendor::create([
            'organization_id' => 2,
            'name' => 'Vendor 1 Tenant 2',
        ]);

        Vendor::create([
            'organization_id' => 2,
            'name' => 'Vendor 2 Tenant 2',
        ]);

    }
}
