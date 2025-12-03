<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {

    User::create([
      'organization_id' => 1,
      'name' => 'Admin User Tenant 1',
      'email' => 'admin@example.com',
      'password' => Hash::make('password12'),
      'role' => 'admin',
    ]);

    User::create([
      'organization_id' => 2,
      'name' => 'Admin User Tenant 2',
      'email' => 'admin2@example.com',
      'password' => Hash::make('password12'),
      'role' => 'admin',
    ]);

    User::create([
      'organization_id' => 1,
      'name' => 'Accountant User Tenant 1',
      'email' => 'accountant@example.com',
      'password' => Hash::make('password12'),
      'role' => 'accountant',
    ]);

  }
}
