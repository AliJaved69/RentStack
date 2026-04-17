<x-mail::message>
# Rent Payment Receipt

Hello,

This is a receipt for the rent payment recorded for **{{ $tenant_name }}**.

**Property:** {{ $property_title }}  
**Amount Paid:** {{ number_format($amount_paid, 2) }}  
**Payment Method:** {{ ucfirst($payment_method) }}  
**Date:** {{ $payment->payment_date }}

**Remaining Balance for this Month:** {{ number_format($remaining_balance, 2) }}

Thanks,  
{{ config('app.name') }}
</x-mail::message>
