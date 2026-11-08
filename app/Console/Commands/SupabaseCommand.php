<?php

namespace App\Console\Commands;

use App\Services\SupabaseService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SupabaseCommand extends Command
{
    protected $signature = 'supabase:sync {action=push : Action to perform (status|push|pull)}';
    protected $description = 'Sync RentStack database with Supabase cloud';

    protected array $tables = [
        'owners',
        'users',
        'properties',
        'property_units',
        'tenants',
        'leases',
        'invoices',
        'payments',
        'ledgers',
    ];

    public function handle(SupabaseService $supabase)
    {
        $action = $this->argument('action');

        if ($action === 'status') {
            $this->info('Testing connection to Supabase (' . config('services.supabase.url') . ')...');
            if ($supabase->ping()) {
                $this->info('✅ Successfully connected to Supabase REST API!');
            } else {
                $this->error('❌ Failed to reach Supabase. Check your SUPABASE_URL and keys.');
            }
            return;
        }

        if ($action === 'push') {
            $this->info('Pushing all local RentStack data to Supabase cloud...');

            foreach ($this->tables as $table) {
                if (!Schema::hasTable($table)) {
                    continue;
                }

                $rows = DB::table($table)->get()->map(fn($r) => (array) $r)->toArray();
                if (empty($rows)) {
                    $this->line("  - {$table}: 0 local records (skipped)");
                    continue;
                }

                $result = $supabase->upsert($table, $rows);
                if ($result['success'] ?? false) {
                    $count = count($rows);
                    $this->info("  ✅ {$table}: {$count} records synced to Supabase.");
                } else {
                    $status = $result['status'] ?? 'error';
                    $msg = is_array($result['data'] ?? null) ? json_encode($result['data']) : ($result['error'] ?? 'Unknown error');
                    $this->warn("  ⚠️ {$table} (HTTP {$status}): {$msg}");
                }
            }

            $this->info('Push operation complete.');
            return;
        }

        if ($action === 'pull') {
            $this->info('Pulling central records from Supabase into local database...');

            foreach ($this->tables as $table) {
                $remoteRows = $supabase->getAll($table);
                if (empty($remoteRows)) {
                    $this->line("  - {$table}: 0 remote records.");
                    continue;
                }

                $inserted = 0;
                foreach ($remoteRows as $row) {
                    if (DB::table($table)->insertOrIgnore($row)) {
                        $inserted++;
                    }
                }

                $this->info("  ✅ {$table}: {$inserted} new records imported from Supabase.");
            }

            $this->info('Pull operation complete.');
            return;
        }

        $this->error("Unknown action: {$action}. Use status, push, or pull.");
    }
}

