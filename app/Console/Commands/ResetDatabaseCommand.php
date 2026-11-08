<?php

namespace App\Console\Commands;

use App\Services\DataSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ResetDatabaseCommand extends Command
{
    protected $signature = 'rentstack:reset {--keep-user : Keep the current login user accounts} {--force : Force reset without prompt}';
    protected $description = 'Totally wipe all properties, units, tenants, leases, and invoices to start completely fresh';

    public function handle()
    {
        $keepUser = $this->option('keep-user');
        $force = $this->option('force');

        if (!$force) {
            $msg = $keepUser 
                ? 'This will delete ALL properties, units, portions, tenants, leases, and payments (user accounts will be preserved). Are you sure?' 
                : 'This will completely WIPE the ENTIRE database (including user accounts). Are you sure?';

            if (!$this->confirm($msg, false)) {
                $this->info('Database reset cancelled.');
                return 0;
            }
        }

        $this->info('🧹 Cleaning all RentStack database tables...');

        try {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } catch (\Throwable $t) {
            //
        }

        $tablesToWipe = [
            'payments',
            'invoices',
            'leases',
            'property_units',
            'tenants',
            'properties',
            'owners',
            'ledgers',
        ];

        if (!$keepUser) {
            $tablesToWipe[] = 'users';
            $tablesToWipe[] = 'sessions';
        }

        foreach ($tablesToWipe as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->delete();
                // Reset SQLite auto-increment counter
                try {
                    DB::statement("DELETE FROM sqlite_sequence WHERE name = '{$table}'");
                } catch (\Throwable $t) {
                    //
                }
                $this->line("  ✓ Wiped table: {$table}");
            }
        }

        try {
            DB::statement('PRAGMA foreign_keys = ON;');
        } catch (\Throwable $t) {
            //
        }

        // Clean up uploaded property pictures
        try {
            $files = Storage::disk('public')->files('properties');
            foreach ($files as $file) {
                Storage::disk('public')->delete($file);
            }
            $this->line("  ✓ Cleaned uploaded property images");
        } catch (\Throwable $t) {
            //
        }

        // Re-export clean initial data snapshot for mobile APK
        DataSyncService::export();
        $this->line("  ✓ Re-exported empty initial_data.json snapshot for mobile APK bundle");

        $this->newLine();
        $this->info('✨ Database has been totally cleaned and reset to brand new state!');
        
        if ($keepUser) {
            $this->line('💡 Your login user account was kept intact so you can log in right away.');
        } else {
            $this->line('💡 All users were wiped. Run `.\artisan.bat migrate:fresh` or register a new user on the register page.');
        }

        return 0;
    }
}

