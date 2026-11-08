<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\RentCycleService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        // Scoped by OwnerScope
        $payments = Payment::with([
            'invoice.lease.tenant',
            'invoice.lease.property',
            'invoice.lease.unit'
        ])->latest()->paginate(15);

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $invoiceId = $request->query('invoice_id');
        $invoice = $invoiceId ? Invoice::with(['lease.tenant', 'lease.property', 'lease.unit'])->findOrFail($invoiceId) : null;
        
        // Show pending or partially paid invoices
        $invoices = Invoice::where('status', '!=', 'paid')
            ->with(['lease.tenant', 'lease.property', 'lease.unit'])
            ->get();
        
        return view('payments.create', compact('invoices', 'invoice'));
    }

    public function store(Request $request, RentCycleService $rentCycle)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'rent_amount' => 'required|numeric|min:0',
            'security_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,easypaisa,jazzcash,bank_transfer',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if (($validated['rent_amount'] + ($validated['security_amount'] ?? 0)) <= 0) {
            return back()->withErrors(['rent_amount' => 'Please enter an amount received for rent or security deposit.']);
        }

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        $payment = $rentCycle->recordPayment($invoice, $validated);

        // Build WhatsApp receipt link
        $tenantPhone = preg_replace('/[^0-9]/', '', $invoice->lease->tenant->phone ?? '');
        // Standardize Pakistani numbers (e.g. 0300... -> 92300...)
        if (str_starts_with($tenantPhone, '03')) {
            $tenantPhone = '92' . substr($tenantPhone, 1);
        }

        $receiptText = $rentCycle->buildWhatsAppReceipt($payment);
        $whatsappUrl = "https://wa.me/{$tenantPhone}?text=" . rawurlencode($receiptText);

        return redirect()->back()->with([
            'success' => 'Payment of Rs. ' . number_format($payment->amount, 2) . ' recorded successfully!',
            'whatsapp_url' => $whatsappUrl,
            'receipt_tenant' => $invoice->lease->tenant->name ?? 'Tenant',
        ]);
    }
}
