<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RentCycleService
{
    /**
     * Generate monthly invoices for all active leases and carry forward any arrears.
     */
    public function generateMonthlyInvoices(?string $targetMonth = null): array
    {
        $date = $targetMonth ? Carbon::parse($targetMonth)->startOfMonth() : now()->startOfMonth();
        $monthStr = $date->toDateString();

        $activeLeases = Lease::where('status', 'active')
            ->with(['property', 'unit', 'tenant'])
            ->get();

        $generated = 0;
        $skipped = 0;

        foreach ($activeLeases as $lease) {
            // Check if invoice already exists for this lease in the target month
            $exists = Invoice::where('lease_id', $lease->id)
                ->whereDate('billing_month', $monthStr)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            // Find the most recent previous invoice to calculate arrears (unpaid balance)
            $previousInvoice = Invoice::where('lease_id', $lease->id)
                ->whereDate('billing_month', '<', $monthStr)
                ->orderBy('billing_month', 'desc')
                ->first();

            $arrears = 0.0;
            if ($previousInvoice) {
                $arrears = (float) max(0, $previousInvoice->amount_due - $previousInvoice->amount_paid);
            }

            $baseRent = (float) $lease->base_rent;
            $amountDue = $baseRent + $arrears;

            Invoice::create([
                'lease_id' => $lease->id,
                'billing_month' => $monthStr,
                'base_rent' => $baseRent,
                'arrears' => $arrears,
                'utility_charges' => 0.0,
                'security_due' => 0.0,
                'security_paid' => 0.0,
                'amount_due' => $amountDue,
                'amount_paid' => 0.0,
                'status' => 'unpaid',
            ]);

            $generated++;
        }

        if ($generated > 0) {
            DataSyncService::export();
        }

        return [
            'month' => $date->format('F Y'),
            'generated' => $generated,
            'skipped' => $skipped,
        ];
    }

    /**
     * Record a payment (split rent and security deposit installment).
     */
    public function recordPayment(Invoice $invoice, array $data): Payment
    {
        return DB::transaction(function () use ($invoice, $data) {
            $rentPaid = (float) ($data['rent_amount'] ?? 0);
            $securityPaid = (float) ($data['security_amount'] ?? 0);
            $totalAmount = $rentPaid + $securityPaid;

            // 1. Create Payment record
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'rent_amount' => $rentPaid,
                'security_amount' => $securityPaid,
                'amount' => $totalAmount,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'payment_date' => $data['payment_date'] ?? now()->toDateString(),
                'notes' => $data['notes'] ?? null,
            ]);

            // 2. Update Invoice
            $invoice->amount_paid = (float) ($invoice->amount_paid + $rentPaid);
            $invoice->security_paid = (float) ($invoice->security_paid + $securityPaid);

            if ($invoice->amount_paid >= $invoice->amount_due) {
                $invoice->status = 'paid';
            } elseif ($invoice->amount_paid > 0) {
                $invoice->status = 'partially_paid';
            } else {
                $invoice->status = 'unpaid';
            }
            $invoice->save();

            // 3. Update Lease Security Deposit if partial security installment was paid
            if ($securityPaid > 0) {
                $lease = $invoice->lease;
                $lease->security_deposit_paid = (float) ($lease->security_deposit_paid + $securityPaid);
                $lease->save();
            }

            DataSyncService::export();

            return $payment;
        });
    }

    /**
     * Format a WhatsApp receipt message for the tenant.
     */
    public function buildWhatsAppReceipt(Payment $payment): string
    {
        $invoice = $payment->invoice;
        $lease = $invoice->lease;
        $tenant = $lease->tenant;
        $property = $lease->property;
        $unitName = $lease->unit ? $lease->unit->name : 'Main Unit';

        $method = ucfirst(str_replace('_', ' ', $payment->payment_method));
        $date = $payment->payment_date ? $payment->payment_date->format('d-M-Y') : now()->format('d-M-Y');
        $month = $invoice->month_name;

        $msg = "🏠 *RentStack - Rent Receipt*\n";
        $msg .= "━━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "🏢 *Property:* {$property->title}\n";
        $msg .= "📍 *Portion/Shop:* {$unitName}\n";
        $msg .= "👤 *Tenant:* {$tenant->name}\n";
        $msg .= "📅 *Billing Month:* {$month}\n";
        $msg .= "🗓 *Paid Date:* {$date}\n";
        $msg .= "💳 *Payment Method:* {$method}\n";
        $msg .= "━━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "💵 *Amount Received:* Rs. " . number_format($payment->amount, 2) . "\n";
        if ($payment->rent_amount > 0) {
            $msg .= "  • Rent Portion: Rs. " . number_format($payment->rent_amount, 2) . "\n";
        }
        if ($payment->security_amount > 0) {
            $msg .= "  • Security Installment: Rs. " . number_format($payment->security_amount, 2) . "\n";
        }
        $msg .= "━━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "⚠️ *Remaining Balance:* Rs. " . number_format($invoice->remaining_balance, 2) . "\n";
        $msg .= "🔒 *Security Deposit Paid:* Rs. " . number_format($lease->security_deposit_paid, 2) . " / " . number_format($lease->security_deposit_expected, 2) . "\n";
        $msg .= "━━━━━━━━━━━━━━━━━━━━\n";
        $msg .= "Thank you for the payment!";

        return $msg;
    }
}
