<?php

namespace Tests\Feature;

use App\Models\Owner;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_show_renders_add_portion_button_inside_alpine_scope_and_creates_units(): void
    {
        $owner = Owner::create([
            'name' => 'Test Owner',
            'contact_info' => 'owner@example.com',
        ]);

        $user = User::factory()->create([
            'role' => 'admin',
            'owner_id' => $owner->id,
        ]);

        $property = Property::create([
            'owner_id' => $owner->id,
            'title' => 'Military Accounts',
            'address' => 'Military Accounts',
            'status' => 'occupied',
        ]);

        $existingUnit = PropertyUnit::create([
            'property_id' => $property->id,
            'name' => 'Upper Portion',
            'type' => 'portion',
            'default_rent' => 28000,
            'status' => 'vacant',
        ]);

        // Visit property show page
        $response = $this->actingAs($user)->get(route('properties.show', $property));

        $response->assertOk();
        $response->assertSee('Upper Portion');
        $response->assertSee('flex items-center gap-2 w-full sm:w-auto" x-data', false);
        $response->assertSee('@click.stop="$dispatch(\'open-add-unit-modal\')"', false);
        $response->assertSee('@open-add-unit-modal.window="addUnitModalOpen = true"', false);
        $response->assertSee('+ Add Another Portion / Shop', false);

        // Add another vacant portion
        $storeVacant = $this->actingAs($user)->post(route('properties.units.store', $property), [
            'name' => 'Ground Floor Portion',
            'type' => 'portion',
            'default_rent' => 35000,
        ]);

        $storeVacant->assertRedirect(route('properties.show', $property));
        $this->assertDatabaseHas('property_units', [
            'property_id' => $property->id,
            'name' => 'Ground Floor Portion',
            'type' => 'portion',
            'default_rent' => 35000,
            'status' => 'vacant',
        ]);

        // Add an occupied shop with tenant right away
        $tenant = Tenant::create([
            'name' => 'Tariq Mahmood',
            'phone' => '03001234567',
            'cnic_or_id' => '35202-1234567-1',
        ]);

        $storeOccupied = $this->actingAs($user)->post(route('properties.units.store', $property), [
            'name' => 'Shop 1 - Mobile Accessories',
            'type' => 'shop',
            'default_rent' => 20000,
            'tenant_id' => $tenant->id,
        ]);

        $storeOccupied->assertRedirect(route('properties.show', $property));
        $this->assertDatabaseHas('property_units', [
            'property_id' => $property->id,
            'name' => 'Shop 1 - Mobile Accessories',
            'type' => 'shop',
            'status' => 'occupied',
        ]);
        $this->assertDatabaseHas('leases', [
            'property_id' => $property->id,
            'tenant_id' => $tenant->id,
            'base_rent' => 20000,
            'security_deposit_expected' => 40000,
            'status' => 'active',
        ]);
    }
}

