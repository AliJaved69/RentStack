<?php

namespace App\Console\Commands;

use App\Services\RentService;
use Illuminate\Console\Command;

class ApplyRentIncrements extends Command
{
    protected $signature = 'rent:apply-increments';
    protected $description = 'Apply 10% annual rent increment on lease anniversaries';

    public function handle(RentService $rentService)
    {
        $rentService->applyAnniversaryIncrements();
        $this->info('Lease anniversary increments applied where applicable.');
    }
}
