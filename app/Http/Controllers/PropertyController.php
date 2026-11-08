<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Owner;
use App\Services\DataSyncService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        // Automatically scoped by OwnerScope for Admins
        $properties = Property::with('owner')->latest()->paginate(10);
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

        Property::create($validated);

        // Auto-export snapshot so all properties added on laptop are bundled into the APK
        DataSyncService::export();

        return redirect()->route('properties.index')->with('success', 'Property added and saved to mobile bundle successfully.');
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
        ]);

        $property->update($validated);

        // Keep mobile preload bundle in sync
        DataSyncService::export();

        return redirect()->route('properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        $property->delete();

        // Keep mobile preload bundle in sync
        DataSyncService::export();

        return redirect()->route('properties.index')->with('success', 'Property removed successfully.');
    }

    /**
     * Manual trigger to export all laptop data for mobile bundle and Supabase
     */
    public function exportData()
    {
        DataSyncService::export();

        try {
            if (!empty(config('services.supabase.url')) && !empty(config('services.supabase.secret_key'))) {
                \Illuminate\Support\Facades\Artisan::call('supabase:sync push');
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Supabase auto-sync failed: ' . $e->getMessage());
        }

        return back()->with('success', 'All properties and records have been synced to the mobile APK bundle and Supabase cloud!');
    }
}
