<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Ledger;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role === 'super_admin';
        $currentMonth = Carbon::now()->startOfMonth();

        if ($isSuperAdmin) {
            // Aggregated stats for Super Admin
            $stats = [
                'total_collected_month' => Payment::whereMonth('payment_date', Carbon::now()->month)->sum('amount'),
                'total_pending' => Invoice::where('status', '!=', 'paid')->sum('amount_due') - Invoice::where('status', '!=', 'paid')->sum('amount_paid'),
                'total_borrowed' => Ledger::where('type', 'borrowed')->sum('amount') - Ledger::where('type', 'returned')->sum('amount'),
                'cash_on_hand' => Payment::sum('amount') - Ledger::where('type', 'borrowed')->sum('amount') + Ledger::where('type', 'returned')->sum('amount'),
            ];
        } else {
            // Filtered stats for Owner (automatically scoped by OwnerScope)
            $stats = [
                'properties_count' => Property::count(),
                'vacant_properties' => Property::where('status', 'vacant')->count(),
                'total_collected' => Payment::sum('amount'),
            ];
        }

        return view('dashboard', compact('stats', 'isSuperAdmin'));
    }
}
