<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\Property;
use App\Models\PropertyUnit;
use App\Services\RentCycleService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(RentCycleService $rentCycle)
    {
        // Automatically check & generate current month's invoices on dashboard load
        try {
            $rentCycle->generateMonthlyInvoices();
        } catch (\Throwable $t) {
            //
        }

        $currentMonthStart = Carbon::now()->startOfMonth()->toDateString();
        $currentMonthName = Carbon::now()->format('F Y');

        // Scoped automatically by OwnerScope for non-super_admin
        $propertiesCount = Property::count();
        $unitsCount = PropertyUnit::count();
        $occupiedUnitsCount = PropertyUnit::where('status', 'occupied')->count();
        $vacantUnitsCount = PropertyUnit::where('status', 'vacant')->count();

        // Current month invoices
        $currentInvoices = Invoice::whereDate('billing_month', $currentMonthStart)
            ->with(['lease.property', 'lease.unit', 'lease.tenant'])
            ->get();

        $monthDue = (float) $currentInvoices->sum('amount_due');
        $monthCollected = (float) $currentInvoices->sum('amount_paid');
        $monthRemaining = (float) max(0, $monthDue - $monthCollected);
        $totalArrears = (float) $currentInvoices->sum('arrears');

        // Unpaid or partially paid portions needing attention
        $pendingInvoices = $currentInvoices->filter(fn($inv) => $inv->remaining_balance > 0)->values();

        // Recent payments
        $recentPayments = Payment::with(['invoice.lease.property', 'invoice.lease.unit', 'invoice.lease.tenant'])
            ->latest('payment_date')
            ->take(6)
            ->get();

        $stats = [
            'properties_count' => $propertiesCount,
            'units_count' => $unitsCount,
            'occupied_units_count' => $occupiedUnitsCount,
            'vacant_units_count' => $vacantUnitsCount,
            'month_due' => $monthDue,
            'month_collected' => $monthCollected,
            'month_remaining' => $monthRemaining,
            'total_arrears' => $totalArrears,
            'month_name' => $currentMonthName,
        ];

        return view('dashboard', compact('stats', 'pendingInvoices', 'recentPayments'));
    }

    /**
     * Manually trigger rent reset / rollover for the month.
     */
    public function triggerRollover(RentCycleService $rentCycle)
    {
        $res = $rentCycle->generateMonthlyInvoices();
        return back()->with('success', "Rent cycle refreshed for {$res['month']}! Generated {$res['generated']} invoices.");
    }
}
