<?php

namespace App\Services\Installer;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Throwable;

class InstallationService
{
    public const LOCK_FILE = 'installed.lock';

    public function lockPath(): string
    {
        return storage_path(self::LOCK_FILE);
    }

    public function isInstalled(): bool
    {
        $installFlag = $this->envInstallFlag();

        if ($installFlag === null) {
            return false;
        }

        return filter_var($installFlag, FILTER_VALIDATE_BOOLEAN);
    }

    public function createLockFile(): void
    {
        File::put($this->lockPath(), 'Installed at: ' . now()->toDateTimeString() . PHP_EOL);
    }

    private function envInstallFlag(): ?string
    {
        $path = base_path('.env');

        if (! File::exists($path)) {
            return env('APP_INSTALL');
        }

        if (preg_match('/^APP_INSTALL=(.*)$/m', File::get($path), $matches)) {
            return trim($matches[1], " \t\n\r\0\x0B\"'");
        }

        return null;
    }

    public function canConnectToDatabase(array $credentials): array
    {
        $connection = $this->useDatabaseCredentials($credentials);

        try {
            DB::connection($connection)->getPdo();
            return ['ok' => true, 'message' => 'Database connection successful.'];
        } catch (Throwable $exception) {
            return ['ok' => false, 'message' => $exception->getMessage()];
        }
    }

    public function databaseIsPrepared(array $credentials = []): array
    {
        try {
            if ($credentials) {
                $this->useDatabaseCredentials($credentials);
            }

            $migrated = Schema::hasTable('migrations') && DB::table('migrations')->count() > 0;
            $adminSeeded = Schema::hasTable('admins') && DB::table('admins')->count() > 0;
            $countrySeeded = Schema::hasTable('countries') && DB::table('countries')->count() > 0;

            return [
                'ok' => $migrated && $adminSeeded && $countrySeeded,
                'migrated' => $migrated,
                'seeded' => $adminSeeded && $countrySeeded,
                'admin_seeded' => $adminSeeded,
                'country_seeded' => $countrySeeded,
                'message' => $migrated && $adminSeeded && $countrySeeded
                    ? 'This database already has migrations and seed data. You can continue to admin setup.'
                    : 'This database still needs migration and seed data.',
            ];
        } catch (Throwable $exception) {
            return [
                'ok' => false,
                'migrated' => false,
                'seeded' => false,
                'admin_seeded' => false,
                'country_seeded' => false,
                'message' => $exception->getMessage(),
            ];
        }
    }

    public function seededAdmin(): ?array
    {
        if (! Schema::hasTable('admins')) {
            return null;
        }

        $admin = DB::table('admins')->where('id', 1)->first();

        if (! $admin) {
            return null;
        }

        return [
            'name' => trim(($admin->f_name ?? '') . ' ' . ($admin->l_name ?? '')),
            'email' => $admin->email ?? '',
            'phone' => $admin->phone ?? '',
        ];
    }

    public function runMigrationsAndSeeders(array $credentials = []): array
    {
        try {
            if ($credentials) {
                $this->useDatabaseCredentials($credentials);
            }

            $prepared = $this->databaseIsPrepared();

            if ($prepared['ok']) {
                return [
                    'ok' => true,
                    'skipped' => true,
                    'message' => $prepared['message'],
                    'output' => '',
                ];
            }

            Artisan::call('migrate', ['--force' => true]);
            $migrationOutput = Artisan::output();

            if (File::isDirectory(base_path('vendor/laravel/passport/database/migrations'))) {
                Artisan::call('migrate', [
                    '--path' => 'vendor/laravel/passport/database/migrations',
                    '--force' => true,
                ]);
                $migrationOutput .= PHP_EOL . Artisan::output();
            }

            $seedOutput = '';
            $preparedAfterMigration = $this->databaseIsPrepared();

            if (! $preparedAfterMigration['admin_seeded'] && ! $preparedAfterMigration['country_seeded']) {
                Artisan::call('db:seed', ['--force' => true]);
                $seedOutput = Artisan::output();
            } elseif (! $preparedAfterMigration['admin_seeded']) {
                Artisan::call('db:seed', ['--class' => 'AdminTableSeeder', '--force' => true]);
                $seedOutput = Artisan::output();
            } elseif (! $preparedAfterMigration['country_seeded']) {
                Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\CountriesTableSeeder', '--force' => true]);
                $seedOutput = Artisan::output();
            }

            return [
                'ok' => true,
                'message' => 'Database migrated and seeded successfully.',
                'output' => trim($migrationOutput . PHP_EOL . $seedOutput),
            ];
        } catch (Throwable $exception) {
            return ['ok' => false, 'message' => $exception->getMessage(), 'output' => Artisan::output()];
        }
    }

    private function useDatabaseCredentials(array $credentials): string
    {
        $connection = config('database.default');

        config([
            "database.connections.$connection.host" => $credentials['DB_HOST'],
            "database.connections.$connection.port" => $credentials['DB_PORT'],
            "database.connections.$connection.database" => $credentials['DB_DATABASE'],
            "database.connections.$connection.username" => $credentials['DB_USERNAME'],
            "database.connections.$connection.password" => $credentials['DB_PASSWORD'] ?? '',
        ]);

        DB::purge($connection);

        return $connection;
    }

    public function createAdmin(array $data): void
    {
        DB::transaction(function () use ($data) {
            if (Schema::hasTable('admin_roles')) {
                DB::table('admin_roles')->updateOrInsert(
                    ['id' => 1],
                    [
                        'name' => 'Master Admin',
                        'module_access' => null,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            $nameParts = preg_split('/\s+/', trim($data['name']), 2);

            $adminValues = [
                'f_name' => $nameParts[0] ?? $data['name'],
                'l_name' => $nameParts[1] ?? '',
                'phone' => $data['phone'] ?? '',
                'email' => $data['email'],
                'admin_role_id' => 1,
                'status' => 1,
                'remember_token' => Str::random(60),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (! empty($data['password'])) {
                $adminValues['password'] = Hash::make($data['password']);
            }

            if (! array_key_exists('password', $adminValues) && ! DB::table('admins')->where('id', 1)->exists()) {
                $adminValues['password'] = Hash::make(Str::random(32));
            }

            DB::table('admins')->updateOrInsert(['id' => 1], $adminValues);

            if (Schema::hasTable('business_settings')) {
                $settings = $this->defaultBusinessSettings($data['app_name']);
                $settings['footer_text'] = $data['footer_text'] ?? $settings['footer_text'];
                $settings['minimum_order_value'] = $data['minimum_order_value'] ?? $settings['minimum_order_value'];
                $settings['logo'] = $data['logo'] ?? $settings['logo'];
                $settings['fav_icon'] = $data['fav_icon'] ?? $settings['fav_icon'];

                foreach ($settings as $key => $value) {
                    DB::table('business_settings')->updateOrInsert(
                        ['key' => $key],
                        ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
                    );
                }
            }

            $this->seedCurrenciesIfMissing();
        });
    }

    private function defaultBusinessSettings(string $appName): array
    {
        return [
            'restaurant_name' => $appName,
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

    private function seedCurrenciesIfMissing(): void
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

    public function optimizeApplication(): void
    {
        try {
            if (! config('app.key')) {
                Artisan::call('key:generate', ['--force' => true]);
            }

            Artisan::call('optimize:clear');

            if (! File::exists(public_path('storage'))) {
                Artisan::call('storage:link');
            }

            if (app()->runningInConsole()) {
                Artisan::call('config:cache');
            }
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    public function defaultEnvironment(): array
    {
        return [
            'APP_NAME' => env('APP_NAME', 'RestaurantPizzeria'),
            'APP_URL' => env('APP_URL', URL::to('/')),
            'APP_DEBUG' => env('APP_DEBUG', false) ? 'true' : 'false',
            'DB_HOST' => env('DB_HOST', '127.0.0.1'),
            'DB_PORT' => env('DB_PORT', '3306'),
            'DB_DATABASE' => env('DB_DATABASE', ''),
            'DB_USERNAME' => env('DB_USERNAME', ''),
            'DB_PASSWORD' => env('DB_PASSWORD', ''),
        ];
    }
}
