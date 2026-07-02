<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ResetDemoDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:reset-database {--force : Run without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear demo database data and recreate the required admin and branch accounts.';

    /**
     * Tables that keep application configuration/reference data.
     *
     * @var array
     */
    protected array $protectedTables = [
        'migrations',
        'business_settings',
        'currencies',
        'countries',
        'soft_credentials',
        'oauth_clients',
        'oauth_personal_access_clients',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! $this->option('force') && ! $this->confirm('This will delete demo database rows. Continue?')) {
            $this->info('Demo database reset cancelled.');
            return 0;
        }

        $tables = $this->baseTables();
        $tablesToTruncate = array_values(array_diff($tables, $this->protectedTables));

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            foreach ($tablesToTruncate as $table) {
                DB::table($table)->truncate();
                $this->line("Truncated: {$table}");
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->seedRequiredSettings();

        require_once database_path('seeders/AdminTableSeeder.php');
        require_once database_path('seeders/BranchTableSeeder.php');

        (new \AdminTableSeeder())->run();
        (new \BranchTableSeeder())->run();

        if (Schema::hasTable('countries') && DB::table('countries')->count() === 0) {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\CountriesTableSeeder',
                '--force' => true,
            ]);
        }

        $this->info('Demo database reset completed. Admin: admin@admin.com / 12345678. Branch: mainb@mainb.com / 12345678.');

        return 0;
    }

    /**
     * @return array
     */
    protected function baseTables(): array
    {
        $rows = DB::select("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");

        return collect($rows)
            ->map(function ($row) {
                return array_values((array) $row)[0] ?? null;
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return void
     */
    protected function seedRequiredSettings(): void
    {
        if (Schema::hasTable('business_settings')) {
            foreach ($this->defaultBusinessSettings() as $key => $value) {
                DB::table('business_settings')->updateOrInsert(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }

        if (Schema::hasTable('currencies') && DB::table('currencies')->count() === 0) {
            $currencies = $this->currencyRows();

            foreach (array_chunk($currencies, 100) as $chunk) {
                DB::table('currencies')->insert($chunk);
            }
        }
    }

    /**
     * @return array
     */
    protected function defaultBusinessSettings(): array
    {
        return [
            'restaurant_name' => 'Restaurant Pizzeria',
            'logo' => '',
            'fav_icon' => '',
            'footer_text' => '',
            'currency' => 'USD',
            'currency_symbol_position' => 'left',
            'decimal_point_settings' => '2',
            'pagination_limit' => '10',
            'minimum_order_value' => '0',
            'point_per_currency' => '1',
            'time_zone' => config('app.timezone', 'UTC'),
            'time_format' => '24',
            'overall_tax' => '0',
            'default_preparation_time' => '30',
            'schedule_order_slot_duration' => '1',
            'order_confirmation_picture' => '0',
            'self_pickup' => '1',
            'delivery' => '1',
            'email_verification' => '0',
            'phone_verification' => '0',
            'dm_self_registration' => '1',
            'toggle_veg_non_veg' => '1',
            'status' => '1',
            'staus' => '1',
            'delivery_charge' => '0',
            'push_notification_key' => '',
            'map_api_server_key' => '',
            'cash_on_delivery' => json_encode([
                'status' => 0,
            ]),
            'digital_payment' => json_encode([
                'status' => 0,
            ]),
            'payconiq_payment' => json_encode([
                'status' => 0,
                'token' => '',
            ]),
            'ssl_commerz_payment' => json_encode([
                'status' => 0,
                'store_id' => '',
                'store_password' => '',
            ]),
            'razor_pay' => json_encode([
                'status' => 0,
                'razor_key' => '',
                'razor_secret' => '',
            ]),
            'paypal' => json_encode([
                'status' => 0,
                'paypal_client_id' => '',
                'paypal_secret' => '',
            ]),
            'stripe' => json_encode([
                'status' => 0,
                'published_key' => '',
                'api_key' => '',
            ]),
            'language' => json_encode([[
                'id' => 1,
                'name' => 'English',
                'code' => 'en',
                'status' => 1,
                'default' => true,
            ]]),
            'wolt_service' => json_encode([
                'venue_id' => '',
                'merchant_id' => '',
                'token' => '',
                'status' => '0',
                'environment' => 'sandbox',
            ]),
        ];
    }

    /**
     * @return array
     */
    protected function currencyRows(): array
    {
        $path = base_path('installation/currency.json');
        $currencies = File::exists($path) ? json_decode(File::get($path), true) : [];

        if (! is_array($currencies) || $currencies === []) {
            $currencies = [[
                'name' => 'United States',
                'code' => 'USD',
                'symbol_native' => '$',
            ]];
        }

        return collect($currencies)->map(function ($currency) {
            return [
                'country' => $currency['name'] ?? $currency['code'] ?? null,
                'currency_code' => $currency['code'] ?? null,
                'currency_symbol' => $currency['symbol_native'] ?? $currency['symbol'] ?? null,
                'exchange_rate' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();
    }
}
