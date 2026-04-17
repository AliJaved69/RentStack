<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Owner;
use App\Models\Property;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@rentstack.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // 2. Create sample Owner
        $owner = Owner::create([
            'name' => 'John Doe (Owner)',
            'contact_info' => 'john@example.com',
        ]);

        // 3. Create Admin User linked to Owner
        User::create([
            'name' => 'John Doe',
            'email' => 'john@rentstack.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'owner_id' => $owner->id,
        ]);

        // 4. Create sample Properties
        Property::create([
            'owner_id' => $owner->id,
            'title' => 'Sample Apartment A',
            'address' => '123 Main St',
            'status' => 'vacant',
        ]);

        Property::create([
            'owner_id' => $owner->id,
            'title' => 'Sample Villa B',
            'address' => '456 Oak Ave',
            'status' => 'occupied',
        ]);
    }
}
