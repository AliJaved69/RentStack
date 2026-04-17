<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Property;
use App\Models\Tenant;
use Illuminate\Http\Request;

class LeaseController extends Controller
{
    public function index()
    {
        // Scoped by OwnerScope
        $leases = Lease::with(['property', 'tenant'])->paginate(10);
        return view('leases.index', compact('leases'));
    }

    public function create()
    {
        $properties = Property::where('status', 'vacant')->get();
        $tenants = Tenant::all();
        return view('leases.create', compact('properties', 'tenants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
            'base_rent' => 'required|numeric',
            'security_deposit_expected' => 'required|numeric',
            'security_deposit_paid' => 'required|numeric',
            'e_stamp' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        if ($request->hasFile('e_stamp')) {
            $path = $request->file('e_stamp')->store('e_stamps', 'public');
            $validated['e_stamp_file_path'] = $path;
        }

        $lease = Lease::create($validated);

        // Mark property as occupied
        $lease->property->update(['status' => 'occupied']);

        return redirect()->route('leases.index')->with('success', 'Lease created successfully.');
    }

    public function terminate(Lease $lease)
    {
        return view('leases.terminate', compact('lease'));
    }

    public function settle(Request $request, Lease $lease, \App\Services\RentService $rentService)
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

        // Terminate lease and free up property
        $lease->update(['status' => 'terminated']);
        $lease->property->update(['status' => 'vacant']);

        return redirect()->route('leases.index')->with('success', "Lease terminated. Refund amount: $" . number_format($settlement['refund_amount'], 2));
    }
}
