<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SeedDefaultCurrencies extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('currencies') || DB::table('currencies')->count() > 0) {
            return;
        }

        $path = base_path('installation/currency.json');
        $currencies = File::exists($path) ? json_decode(File::get($path), true) : [];

        if (! is_array($currencies) || $currencies === []) {
            $currencies = [
                'USD' => [
                    'name' => 'US Dollar',
                    'code' => 'USD',
                    'symbol_native' => '$',
                ],
            ];
        }

        $now = now();
        $rows = [];

        foreach ($currencies as $currency) {
            $rows[] = [
                'country' => $currency['name'] ?? $currency['code'] ?? null,
                'currency_code' => $currency['code'] ?? null,
                'currency_symbol' => $currency['symbol_native'] ?? $currency['symbol'] ?? null,
                'exchange_rate' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table('currencies')->insert($chunk);
        }
    }

    public function down(): void
    {
        //
    }
}
