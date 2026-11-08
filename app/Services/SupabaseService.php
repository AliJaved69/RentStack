<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseService
{
    protected string $url;
    protected string $secretKey;
    protected string $publishableKey;

    public function __construct()
    {
        $this->url = rtrim(config('services.supabase.url') ?? env('SUPABASE_URL', ''), '/');
        $this->secretKey = config('services.supabase.secret_key') ?? env('SUPABASE_SECRET_KEY', '');
        $this->publishableKey = config('services.supabase.publishable_key') ?? env('SUPABASE_PUBLISHABLE_KEY', '');
    }

    /**
     * Get an authorized HTTP client for Supabase REST API.
     */
    protected function client(bool $useSecret = true)
    {
        $key = $useSecret ? $this->secretKey : $this->publishableKey;

        return Http::baseUrl($this->url . '/rest/v1')
            ->withHeaders([
                'apikey' => $key,
                'Authorization' => 'Bearer ' . $key,
                'Content-Type' => 'application/json',
                'Prefer' => 'return=representation',
            ]);
    }

    /**
     * Check if Supabase connection is healthy.
     */
    public function ping(): bool
    {
        try {
            $response = $this->client()->get('/');
            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Supabase ping failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetch all records from a Supabase table.
     */
    public function getAll(string $table, array $params = []): array
    {
        try {
            $response = $this->client()->get("/{$table}", $params);
            return $response->successful() ? $response->json() : [];
        } catch (\Throwable $e) {
            Log::error("Supabase getAll({$table}) error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Upsert records into a Supabase table.
     */
    public function upsert(string $table, array $records, string $onConflict = 'id'): array
    {
        try {
            $response = $this->client()
                ->withHeaders([
                    'Prefer' => 'resolution=merge-duplicates,return=representation',
                ])
                ->post("/{$table}", $records);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json(),
            ];
        } catch (\Throwable $e) {
            Log::error("Supabase upsert({$table}) error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete a record from Supabase table.
     */
    public function delete(string $table, int|string $id): bool
    {
        try {
            $response = $this->client()->delete("/{$table}?id=eq.{$id}");
            return $response->successful();
        } catch (\Throwable $e) {
            Log::error("Supabase delete({$table}, {$id}) error: " . $e->getMessage());
            return false;
        }
    }
}

