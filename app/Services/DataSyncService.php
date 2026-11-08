<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class DataSyncService
{
    protected static string $exportFilePath = 'database/data/initial_data.json';

    /**
     * Tables to sync between laptop and mobile APK.
     */
    protected static array $tables = [
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

    /**
     * Get the absolute path to the initial data JSON file.
     */
    public static function getFilePath(): string
    {
        return base_path(self::$exportFilePath);
    }

    /**
     * Export all laptop database data to initial_data.json.
     * This JSON snapshot is bundled into the APK laravel_bundle.zip.
     */
    public static function export(): array
    {
        try {
            $dir = dirname(self::getFilePath());
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $counts = [];
            $data = [
                'exported_at' => now()->toIso8601String(),
                'version' => '1.0',
                'tables' => [],
            ];

            foreach (self::$tables as $table) {
                if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                    $rows = DB::table($table)->get()->map(function ($row) {
                        return (array) $row;
                    })->toArray();

                    $data['tables'][$table] = $rows;
                    $counts[$table] = count($rows);
                }
            }

            File::put(self::getFilePath(), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            
            // Also bundle local property images into database/data/property_images/ for mobile APK
            $sourceImgDir = storage_path('app/public/properties');
            $targetImgDir = base_path('database/data/property_images');
            if (File::exists($sourceImgDir)) {
                if (!File::exists($targetImgDir)) {
                    File::makeDirectory($targetImgDir, 0755, true);
                }
                File::cleanDirectory($targetImgDir);
                File::copyDirectory($sourceImgDir, $targetImgDir);
            }

            return [
                'success' => true,
                'counts' => $counts,
                'path' => self::getFilePath(),
            ];
        } catch (\Throwable $e) {
            Log::error('DataSync export failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Import preloaded initial data if the database is fresh or empty.
     * Automatically invoked on first mobile app boot in AppServiceProvider.
     */
    public static function importIfEmpty(bool $force = false): array
    {
        try {
            $filePath = self::getFilePath();
            if (!File::exists($filePath)) {
                return ['success' => false, 'message' => 'No initial data file found.'];
            }

            // If not forced, only import if there are no properties or users
            if (!$force) {
                $hasProperties = \Illuminate\Support\Facades\Schema::hasTable('properties') && DB::table('properties')->count() > 0;
                $hasUsers = \Illuminate\Support\Facades\Schema::hasTable('users') && DB::table('users')->count() > 0;

                if ($hasProperties && $hasUsers) {
                    return ['success' => false, 'message' => 'Database already populated.'];
                }
            }

            $content = File::get($filePath);
            $data = json_decode($content, true);
            if (!$data || !isset($data['tables'])) {
                return ['success' => false, 'message' => 'Invalid initial data format.'];
            }

            $importedCounts = [];

            // Disable foreign keys temporarily for clean relational insertion in SQLite
            try {
                DB::statement('PRAGMA foreign_keys = OFF;');
            } catch (\Throwable $t) {
                // Ignore if driver doesn't support PRAGMA
            }

            DB::transaction(function () use ($data, &$importedCounts) {
                foreach (self::$tables as $table) {
                    if (!isset($data['tables'][$table]) || empty($data['tables'][$table])) {
                        continue;
                    }

                    $rows = $data['tables'][$table];
                    $count = 0;

                    foreach ($rows as $row) {
                        $inserted = DB::table($table)->insertOrIgnore($row);
                        if ($inserted) {
                            $count++;
                        }
                    }

                    $importedCounts[$table] = $count;
                }
            });

            try {
                DB::statement('PRAGMA foreign_keys = ON;');
            } catch (\Throwable $t) {
                // Ignore
            }

            // Extract preloaded property images into mobile local storage (storage/app/public/properties/)
            try {
                $bundledImgDir = base_path('database/data/property_images');
                $mobileStorageImgDir = storage_path('app/public/properties');
                if (File::exists($bundledImgDir)) {
                    if (!File::exists($mobileStorageImgDir)) {
                        File::makeDirectory($mobileStorageImgDir, 0755, true);
                    }
                    File::copyDirectory($bundledImgDir, $mobileStorageImgDir);
                }
            } catch (\Throwable $t) {
                Log::warning('DataSync image extraction warning: ' . $t->getMessage());
            }

            return [
                'success' => true,
                'imported' => $importedCounts,
            ];
        } catch (\Throwable $e) {
            Log::error('DataSync import failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
