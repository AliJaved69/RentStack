<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\DataSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class TenantController extends Controller
{
    public function index()
    {
        // Scoped by OwnerScope
        $tenants = Tenant::latest()->paginate(10);
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
            'cnic_or_id' => 'required|string|max:20|unique:tenants,cnic_or_id',
        ]);

        Tenant::create($validated);

        // Keep mobile snapshot and Supabase in sync
        DataSyncService::export();
        $this->syncToSupabase();

        return redirect()->route('tenants.index')->with('success', 'Tenant added successfully.');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['leases.property']);
        return view('tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        return view('tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'cnic_or_id' => 'required|string|max:20|unique:tenants,cnic_or_id,' . $tenant->id,
        ]);

        $tenant->update($validated);

        DataSyncService::export();
        $this->syncToSupabase();

        return redirect()->route('tenants.index')->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        DataSyncService::export();
        $this->syncToSupabase();

        return redirect()->route('tenants.index')->with('success', 'Tenant removed successfully.');
    }

    protected function syncToSupabase(): void
    {
        try {
            if (!empty(config('services.supabase.url')) && !empty(config('services.supabase.secret_key'))) {
                Artisan::call('supabase:sync push');
            }
        } catch (\Throwable $e) {
            Log::warning('Supabase sync warning: ' . $e->getMessage());
        }
    }
}
