<?php

namespace App\Console\Commands;

use App\Services\DataSyncService;
use Illuminate\Console\Command;

class ExportDataForMobile extends Command
{
    protected $signature = 'rentstack:export';
    protected $description = 'Export all properties, owners, and initial data into the mobile APK bundle file (database/data/initial_data.json)';

    public function handle()
    {
        $this->info('Exporting RentStack laptop database to mobile preload bundle...');
        $result = DataSyncService::export();

        if ($result['success'] ?? false) {
            $this->info('✅ Successfully exported data to: ' . ($result['path'] ?? DataSyncService::getFilePath()));
            if (!empty($result['counts'])) {
                $this->table(['Table', 'Records Exported'], collect($result['counts'])->map(fn($count, $table) => [$table, $count]));
            }
            $this->line('💡 When you package your APK, all these properties and records will be pre-installed on the mobile phone!');
        } else {
            $this->error('❌ Failed to export data: ' . ($result['error'] ?? 'Unknown error'));
        }
    }
}
