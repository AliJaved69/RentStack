<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\RentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        // Scoped by OwnerScope
        $payments = Payment::with('invoice.lease.tenant', 'invoice.lease.property')->latest()->paginate(10);
        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $invoiceId = $request->query('invoice_id');
        $invoice = $invoiceId ? Invoice::findOrFail($invoiceId) : null;
        
        // Only show pending or partially paid invoices
        $invoices = Invoice::where('status', '!=', 'paid')->with('lease.tenant', 'lease.property')->get();
        
        return view('payments.create', compact('invoices', 'invoice'));
    }

    public function store(Request $request, RentService $rentService)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer',
            'payment_date' => 'required|date',
        ]);

        $rentService->recordPayment(
            $validated['invoice_id'],
            $validated['amount'],
            $validated['payment_method'],
            $validated['payment_date']
        );

        return redirect()->route('payments.index')->with('success', 'Payment recorded and receipt sent.');
    }
}
