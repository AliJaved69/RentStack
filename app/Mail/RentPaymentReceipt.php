<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RentPaymentReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rent Payment Receipt - ' . $this->payment->invoice->lease->tenant->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payment-receipt',
            with: [
                'tenant_name' => $this->payment->invoice->lease->tenant->name,
                'property_title' => $this->payment->invoice->lease->property->title,
                'amount_paid' => $this->payment->amount,
                'payment_method' => $this->payment->payment_method,
                'remaining_balance' => $this->payment->invoice->amount_due - $this->payment->invoice->amount_paid,
            ],
        );
    }
}
