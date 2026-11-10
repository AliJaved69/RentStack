<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyUnitController;
use App\Http\Controllers\LeaseController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/rent/rollover', [DashboardController::class, 'triggerRollover'])->name('rent.rollover');

    Route::post('sync/cloud', [PropertyController::class, 'syncCloud'])->name('sync.cloud');
    Route::post('properties/export-bundle', [PropertyController::class, 'exportData'])->name('properties.export-bundle');
    Route::resource('properties', PropertyController::class);

    // Property Units & Portions / Shops routes
    Route::post('properties/{property}/units', [PropertyUnitController::class, 'store'])->name('properties.units.store');
    Route::post('units/{unit}/assign', [PropertyUnitController::class, 'assignTenant'])->name('units.assign');
    Route::put('units/{unit}', [PropertyUnitController::class, 'update'])->name('units.update');
    Route::delete('units/{unit}', [PropertyUnitController::class, 'destroy'])->name('units.destroy');

    Route::get('leases/{lease}/terminate', [LeaseController::class, 'terminate'])->name('leases.terminate');
    Route::post('leases/{lease}/settle', [LeaseController::class, 'settle'])->name('leases.settle');
    Route::resource('leases', LeaseController::class);

    Route::resource('tenants', TenantController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('ledger', LedgerController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Fallback direct image route for mobile APK storage (in case Android symlink is inactive)
Route::get('storage/properties/{filename}', function ($filename) {
    $path = storage_path('app/public/properties/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->where('filename', '.*');

require __DIR__ . '/auth.php';
