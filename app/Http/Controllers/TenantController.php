<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        // Scoped by OwnerScope (tenants who have leases with the current owner)
        $tenants = Tenant::paginate(10);
        return view('tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('tenants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'cnic_or_id' => 'required|string|max:20|unique:tenants',
        ]);

        Tenant::create($validated);

        return redirect()->route('tenants.index')->with('success', 'Tenant added successfully.');
    }
}
