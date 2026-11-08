<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Property;
use App\Models\Tenant;
use App\Services\DataSyncService;
use App\Services\RentCycleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index()
    {
        // Make sure current month invoices are generated
        try {
            app(RentCycleService::class)->generateMonthlyInvoices();
        } catch (\Throwable $t) {
            // Log but don't halt
        }

        // Load properties with units count, occupied count, and active leases
        $properties = Property::with([
            'owner',
            'units' => function ($query) {
                $query->with(['activeLease.latestInvoice', 'activeLease.tenant']);
            }
        ])->latest()->paginate(9);

        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        $owners = Owner::all();
        return view('properties.create', compact('owners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'address' => 'required|string',
            'status' => 'required|in:vacant,occupied',
            'owner_id' => 'nullable|exists:owners,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // max 5MB photo
        ]);

        $user = auth()->user();

        // Assign owner_id based on user role or fallback
        if (empty($validated['owner_id'])) {
            $validated['owner_id'] = $user->owner_id;
        }

        if (empty($validated['owner_id'])) {
            $defaultOwner = Owner::firstOrCreate(
                ['name' => $user->name],
                ['contact_info' => $user->email]
            );
            $validated['owner_id'] = $defaultOwner->id;

            if (empty($user->owner_id)) {
                $user->update(['owner_id' => $defaultOwner->id]);
            }
        }

        // Handle property picture upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('properties', 'public');
            $validated['image_path'] = $path;
        }

        $property = Property::create($validated);

        // Auto-export snapshot for mobile bundle
        DataSyncService::export();
        $this->syncToSupabase();

        return redirect()->route('properties.show', $property)->with('success', 'Property added! Now you can add its portions, shops, and tenants below.');
    }

    public function show(Property $property)
    {
        // Make sure current month invoices are generated
        try {
            app(RentCycleService::class)->generateMonthlyInvoices();
        } catch (\Throwable $t) {
            //
        }

        $property->load([
            'owner',
            'units' => function ($query) {
                $query->with([
                    'activeLease.tenant',
                    'activeLease.latestInvoice.payments',
                ]);
            }
        ]);

        $tenants = Tenant::orderBy('name')->get();

        return view('properties.show', compact('property', 'tenants'));
    }

    public function edit(Property $property)
    {
        $owners = Owner::all();
        return view('properties.edit', compact('property', 'owners'));
    }

    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'address' => 'required|string',
            'status' => 'required|in:vacant,occupied',
            'owner_id' => 'nullable|exists:owners,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($property->image_path && Storage::disk('public')->exists($property->image_path)) {
                Storage::disk('public')->delete($property->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('properties', 'public');
        }

        $property->update($validated);

        DataSyncService::export();
        $this->syncToSupabase();

        return redirect()->route('properties.show', $property)->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        if ($property->image_path && Storage::disk('public')->exists($property->image_path)) {
            Storage::disk('public')->delete($property->image_path);
        }

        $property->delete();

        DataSyncService::export();
        $this->syncToSupabase();

        return redirect()->route('properties.index')->with('success', 'Property removed successfully.');
    }

    public function exportData()
    {
        DataSyncService::export();
        $this->syncToSupabase();

        return back()->with('success', 'All properties, portions, and records have been synced to the mobile APK bundle and Supabase cloud!');
    }

    public function syncCloud(Request $request)
    {
        try {
            $direction = $request->input('direction', 'bidirectional');

            if ($direction === 'pull' || $direction === 'bidirectional') {
                Artisan::call('supabase:sync pull');
            }

            if ($direction === 'push' || $direction === 'bidirectional') {
                Artisan::call('supabase:sync push');
            }

            DataSyncService::export();

            return back()->with('success', 'Cloud sync completed successfully! Your data is fully synchronized with Supabase.');
        } catch (\Throwable $e) {
            Log::error('Cloud sync failed: ' . $e->getMessage());
            return back()->with('error', 'Cloud sync failed: ' . $e->getMessage());
        }
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
