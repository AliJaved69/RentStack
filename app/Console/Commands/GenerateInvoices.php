<?php

namespace App\Console\Commands;

use App\Services\RentService;
use Illuminate\Console\Command;

class GenerateInvoices extends Command
{
    protected $signature = 'rent:generate-invoices';
    protected $description = 'Generate monthly invoices for all active leases';

    public function handle(RentService $rentService)
    {
        $rentService->generateMonthlyInvoices();
        $this->info('Monthly invoices generated successfully.');
    }
}
