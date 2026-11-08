<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Services\DataSyncService;
use App\Services\RentCycleService;
use App\Services\RentService;
use Illuminate\Http\Request;

class LeaseController extends Controller
{
    public function index()
    {
        // Scoped by OwnerScope
        $leases = Lease::with(['property', 'unit', 'tenant'])->latest()->paginate(12);
        return view('leases.index', compact('leases'));
    }

    public function create()
    {
        $properties = Property::with(['units' => function ($query) {
            $query->where('status', 'vacant');
        }])->get();

        $tenants = Tenant::orderBy('name')->get();

        return view('leases.create', compact('properties', 'tenants'));
    }

    public function store(Request $request, RentCycleService $rentCycle)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:property_units,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
            'base_rent' => 'required|numeric|min:0',
            'security_deposit_expected' => 'nullable|numeric|min:0',
            'security_deposit_paid' => 'nullable|numeric|min:0',
            'e_stamp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        if (empty($validated['security_deposit_expected'])) {
            $validated['security_deposit_expected'] = (float) $validated['base_rent'] * 2;
        }

        if (empty($validated['security_deposit_paid'])) {
            $validated['security_deposit_paid'] = 0.0;
        }

        if ($request->hasFile('e_stamp')) {
            $path = $request->file('e_stamp')->store('e_stamps', 'public');
            $validated['e_stamp_file_path'] = $path;
        }

        $validated['status'] = 'active';

        $lease = Lease::create($validated);

        // Mark unit or property as occupied
        if (!empty($validated['unit_id'])) {
            PropertyUnit::where('id', $validated['unit_id'])->update(['status' => 'occupied']);
        }
        $lease->property->update(['status' => 'occupied']);

        // Generate current month's invoice immediately
        $rentCycle->generateMonthlyInvoices();
        DataSyncService::export();

        return redirect()->route('leases.index')->with('success', 'Lease agreement created and activated successfully.');
    }

    public function terminate(Lease $lease)
    {
        $lease->load(['property', 'unit', 'tenant', 'latestInvoice']);
        return view('leases.terminate', compact('lease'));
    }

    public function settle(Request $request, Lease $lease, RentService $rentService)
    {
        $validated = $request->validate([
            'electricity' => 'required|numeric|min:0',
            'gas' => 'required|numeric|min:0',
            'water' => 'required|numeric|min:0',
        ]);

        $settlement = $rentService->calculateMoveOutSettlement(
            $lease,
            $validated['electricity'],
            $validated['gas'],
            $validated['water']
        );

        // Terminate lease and free up portion/property
        $lease->update(['status' => 'terminated']);

        if ($lease->unit_id) {
            PropertyUnit::where('id', $lease->unit_id)->update(['status' => 'vacant']);
        }

        // If no more active leases exist on the property, mark property vacant
        $hasActive = Lease::where('property_id', $lease->property_id)->where('status', 'active')->exists();
        if (!$hasActive) {
            $lease->property->update(['status' => 'vacant']);
        }

        DataSyncService::export();

        return redirect()->route('leases.index')->with('success', "Lease terminated. Refund amount to tenant: Rs. " . number_format($settlement['refund_amount'], 2));
    }
}
