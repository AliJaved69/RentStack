<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Services\DataSyncService;
use App\Services\RentCycleService;
use Illuminate\Http\Request;

class PropertyUnitController extends Controller
{
    /**
     * Store a new Unit / Portion / Shop under a Property.
     */
    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:portion,shop,flat,floor,room,other',
            'default_rent' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            // Optional direct tenant assignment on unit creation:
            'tenant_id' => 'nullable|exists:tenants,id',
            'security_deposit_expected' => 'nullable|numeric|min:0',
            'security_deposit_paid' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
        ]);

        $unit = $property->units()->create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'default_rent' => $validated['default_rent'],
            'status' => !empty($validated['tenant_id']) ? 'occupied' : 'vacant',
            'notes' => $validated['notes'] ?? null,
        ]);

        // If a tenant was selected immediately, create their active lease:
        if (!empty($validated['tenant_id'])) {
            $expectedSecurity = !empty($validated['security_deposit_expected']) 
                ? (float) $validated['security_deposit_expected'] 
                : ((float) $validated['default_rent'] * 2);

            $paidSecurity = !empty($validated['security_deposit_paid']) 
                ? (float) $validated['security_deposit_paid'] 
                : 0.0;

            Lease::create([
                'property_id' => $property->id,
                'unit_id' => $unit->id,
                'tenant_id' => $validated['tenant_id'],
                'start_date' => $validated['start_date'] ?? now()->toDateString(),
                'base_rent' => $validated['default_rent'],
                'security_deposit_expected' => $expectedSecurity,
                'security_deposit_paid' => $paidSecurity,
                'status' => 'active',
            ]);

            // Generate first invoice for current month
            app(RentCycleService::class)->generateMonthlyInvoices();
        }

        DataSyncService::export();

        return redirect()->route('properties.show', $property)->with('success', "Unit '{$unit->name}' added successfully.");
    }

    /**
     * Assign a tenant to an existing vacant Unit / Portion.
     */
    public function assignTenant(Request $request, PropertyUnit $unit)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'base_rent' => 'required|numeric|min:0',
            'security_deposit_expected' => 'nullable|numeric|min:0',
            'security_deposit_paid' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
        ]);

        $expectedSecurity = !empty($validated['security_deposit_expected']) 
            ? (float) $validated['security_deposit_expected'] 
            : ((float) $validated['base_rent'] * 2);

        $paidSecurity = !empty($validated['security_deposit_paid']) 
            ? (float) $validated['security_deposit_paid'] 
            : 0.0;

        // End any previous active lease on this unit
        Lease::where('unit_id', $unit->id)->where('status', 'active')->update(['status' => 'terminated']);

        Lease::create([
            'property_id' => $unit->property_id,
            'unit_id' => $unit->id,
            'tenant_id' => $validated['tenant_id'],
            'start_date' => $validated['start_date'],
            'base_rent' => $validated['base_rent'],
            'security_deposit_expected' => $expectedSecurity,
            'security_deposit_paid' => $paidSecurity,
            'status' => 'active',
        ]);

        $unit->update([
            'default_rent' => $validated['base_rent'],
            'status' => 'occupied',
        ]);

        // Generate invoice for current month
        app(RentCycleService::class)->generateMonthlyInvoices();
        DataSyncService::export();

        return redirect()->route('properties.show', $unit->property_id)->with('success', "Tenant assigned to '{$unit->name}' successfully.");
    }

    /**
     * Update unit details.
     */
    public function update(Request $request, PropertyUnit $unit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:portion,shop,flat,floor,room,other',
            'default_rent' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $unit->update($validated);
        DataSyncService::export();

        return redirect()->route('properties.show', $unit->property_id)->with('success', "Unit updated successfully.");
    }

    /**
     * Vacate or delete a unit.
     */
    public function destroy(PropertyUnit $unit)
    {
        $propertyId = $unit->property_id;
        $unit->leases()->update(['status' => 'terminated']);
        $unit->delete();

        DataSyncService::export();

        return redirect()->route('properties.show', $propertyId)->with('success', "Unit removed successfully.");
    }
}

