<?php

namespace App\Console\Commands;

use App\Services\DataSyncService;
use Illuminate\Console\Command;

class ImportDataForMobile extends Command
{
    protected $signature = 'rentstack:import {--force : Force import even if database already has records}';
    protected $description = 'Import preloaded properties, owners, and initial data from the mobile APK bundle file';

    public function handle()
    {
        $this->info('Importing RentStack initial data from bundle file...');
        $force = (bool) $this->option('force');
        $result = DataSyncService::importIfEmpty($force);

        if ($result['success'] ?? false) {
            $this->info('✅ Successfully imported bundle data:');
            if (!empty($result['imported'])) {
                $this->table(['Table', 'Records Inserted'], collect($result['imported'])->map(fn($count, $table) => [$table, $count]));
            }
        } else {
            $this->warn('ℹ️ ' . ($result['message'] ?? 'Import skipped or not needed. Use --force to override.'));
        }
    }
}

