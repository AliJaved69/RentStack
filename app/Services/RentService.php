<?php

namespace App\Services;

use App\Models\Lease;
use App\Models\Invoice;
use App\Models\Payment;
use App\Mail\RentPaymentReceipt;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class RentService
{
    /**
     * Generate invoices for the current month for all active leases.
     */
    public function generateMonthlyInvoices()
    {
        $leases = Lease::where('status', 'active')->get();
        $billingMonth = Carbon::now()->startOfMonth();

        foreach ($leases as $lease) {
            // Avoid duplicate invoices for the same month
            $exists = Invoice::where('lease_id', $lease->id)
                ->where('billing_month', $billingMonth)
                ->exists();

            if (!$exists) {
                Invoice::create([
                    'lease_id' => $lease->id,
                    'billing_month' => $billingMonth,
                    'amount_due' => $lease->base_rent,
                    'amount_paid' => 0,
                    'status' => 'pending',
                ]);
            }
        }
    }

    /**
     * Apply 10% annual rent increment on the lease anniversary.
     */
    public function applyAnniversaryIncrements()
    {
        $leases = Lease::where('status', 'active')->get();
        $today = Carbon::today();

        foreach ($leases as $lease) {
            $startDate = Carbon::parse($lease->start_date);
            
            // If it's an anniversary (same day and month, but different year)
            if ($today->day === $startDate->day && $today->month === $startDate->month && $today->year > $startDate->year) {
                $newRent = $lease->base_rent * 1.10;
                $lease->update([
                    'base_rent' => $newRent
                ]);
                
                // Log this in system or notify admin? (Requirement only asks to increase)
            }
        }
    }

    /**
     * Record a payment and update the related invoice status.
     */
    public function recordPayment($invoiceId, $amount, $method, $date = null)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $date = $date ?: Carbon::today();

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $amount,
            'payment_method' => $method,
            'payment_date' => $date,
        ]);

        $invoice->amount_paid += $amount;

        if ($invoice->amount_paid >= $invoice->amount_due) {
            $invoice->status = 'paid';
        } elseif ($invoice->amount_paid > 0) {
            $invoice->status = 'partially_paid';
        }

        $invoice->save();

        // Send Receipt Notification
        $this->sendReceipt($payment);

        return $payment;
    }

    /**
     * Send payment receipt email to configured recipients.
     */
    protected function sendReceipt(Payment $payment)
    {
        $recipients = [
            config('mail.recipients.father'),
            config('mail.recipients.mother'),
            config('mail.recipients.admin'),
        ];

        Mail::to($recipients)->send(new RentPaymentReceipt($payment));
    }

    /**
     * Calculate move-out settlement and final refund.
     */
    public function calculateMoveOutSettlement(Lease $lease, $electricityCost, $gasCost, $waterCost)
    {
        $totalDeductions = $electricityCost + $gasCost + $waterCost;
        $refundAmount = $lease->security_deposit_paid - $totalDeductions;

        return [
            'security_deposit' => $lease->security_deposit_paid,
            'deductions' => $totalDeductions,
            'refund_amount' => $refundAmount,
        ];
    }
}
