<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Services\Installer\EnvironmentManager;
use App\Services\Installer\InstallationService;
use App\Services\Installer\RequirementChecker;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class InstallController extends Controller
{
    public function __construct(
        private RequirementChecker $requirements,
        private EnvironmentManager $environment,
        private InstallationService $installation
    ) {
    }

    public function step0(): Factory|View|Application
    {
        return view('installation.step0', [
            'phpVersion' => PHP_VERSION,
            'appName' => env('APP_NAME', 'RestaurantPizzeria'),
        ]);
    }

    public function step1(): Factory|View|Application
    {
        return view('installation.step1', $this->requirements->check());
    }

    public function step2(): Factory|View|Application
    {
        return view('installation.step2', [
            'environment' => $this->installation->defaultEnvironment(),
        ]);
    }

    public function saveEnvironment(Request $request): RedirectResponse
    {
        $data = Validator::make($request->all(), [
            'APP_NAME' => ['required', 'string', 'max:80'],
            'APP_URL' => ['required', 'url', 'max:255'],
            'APP_DEBUG' => ['required', Rule::in(['true', 'false'])],
            'DB_HOST' => ['required', 'string', 'max:255'],
            'DB_PORT' => ['required', 'integer', 'between:1,65535'],
            'DB_DATABASE' => ['required', 'string', 'max:128'],
            'DB_USERNAME' => ['required', 'string', 'max:128'],
            'DB_PASSWORD' => ['nullable', 'string', 'max:255'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'minimum_order_value' => ['nullable', 'numeric', 'min:0'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'fav_icon' => ['nullable', 'image', 'max:2048'],
        ])->validate();

        $existingBusinessSettings = session('installer.business_settings', []);

        $businessSettings = [
            'footer_text' => $data['footer_text'] ?? '',
            'minimum_order_value' => $data['minimum_order_value'] ?? 0,
            'logo' => $existingBusinessSettings['logo'] ?? '',
            'fav_icon' => $existingBusinessSettings['fav_icon'] ?? '',
        ];

        if ($request->hasFile('logo')) {
            $businessSettings['logo'] = Helpers::update('restaurant/', $businessSettings['logo'], 'png', $request->file('logo'));
        }

        if ($request->hasFile('fav_icon')) {
            $businessSettings['fav_icon'] = Helpers::update('restaurant/', $businessSettings['fav_icon'], 'png', $request->file('fav_icon'));
        }

        unset($data['footer_text'], $data['minimum_order_value'], $data['logo'], $data['fav_icon']);

        try {
            $this->environment->update(array_merge($data, [
                'APP_ENV' => 'production',
                'APP_INSTALL' => 'false',
                'APP_MODE' => env('APP_MODE', 'live'),
                'DB_CONNECTION' => 'mysql',
                'QUEUE_CONNECTION' => env('QUEUE_CONNECTION', 'sync'),
                'CACHE_DRIVER' => env('CACHE_DRIVER', 'file'),
                'SESSION_DRIVER' => env('SESSION_DRIVER', 'file'),
            ]));

            session(['installer.environment' => $data]);
            session(['installer.business_settings' => $businessSettings]);

            return redirect()->route('install.database')->with('success', '.env updated successfully.');
        } catch (Throwable $exception) {
            return back()->withInput()->withErrors(['environment' => $exception->getMessage()]);
        }
    }

    public function step3(): Factory|View|Application
    {
        return view('installation.step3', [
            'environment' => session('installer.environment', $this->installation->defaultEnvironment()),
            'connection' => session('installer.connection'),
        ]);
    }

    public function testDatabase(Request $request): RedirectResponse
    {
        $data = Validator::make($request->all(), [
            'DB_HOST' => ['required', 'string', 'max:255'],
            'DB_PORT' => ['required', 'integer', 'between:1,65535'],
            'DB_DATABASE' => ['required', 'string', 'max:128'],
            'DB_USERNAME' => ['required', 'string', 'max:128'],
            'DB_PASSWORD' => ['nullable', 'string', 'max:255'],
        ])->validate();

        $result = $this->installation->canConnectToDatabase($data);
        session(['installer.environment' => array_merge(session('installer.environment', []), $data)]);
        session(['installer.connection' => $result]);

        if (! $result['ok']) {
            return back()->withInput()->withErrors(['database' => $result['message']]);
        }

        return redirect()->route('install.migrations')->with('success', $result['message']);
    }

    public function step4(): Factory|View|Application
    {
        $prepared = ['ok' => false, 'migrated' => false, 'seeded' => false, 'message' => null];

        if (data_get(session('installer.connection'), 'ok')) {
            $prepared = $this->installation->databaseIsPrepared(session('installer.environment', []));
        }

        return view('installation.step4', [
            'ready' => (bool) data_get(session('installer.connection'), 'ok'),
            'result' => session('installer.migration'),
            'prepared' => $prepared,
        ]);
    }

    public function runMigrations(): RedirectResponse
    {
        if (! data_get(session('installer.connection'), 'ok')) {
            return redirect()->route('install.database')->withErrors(['database' => 'Test the database connection before running migrations.']);
        }

        $credentials = session('installer.environment', []);
        $prepared = $this->installation->databaseIsPrepared($credentials);

        if ($prepared['ok']) {
            session(['installer.migration' => [
                'ok' => true,
                'skipped' => true,
                'message' => $prepared['message'],
                'output' => '',
            ]]);

            return redirect()->route('install.admin')->with('success', $prepared['message']);
        }

        $result = $this->installation->runMigrationsAndSeeders($credentials);
        session(['installer.migration' => $result]);

        if (! $result['ok']) {
            return back()->withErrors(['migration' => $result['message']]);
        }

        return redirect()->route('install.admin')->with('success', $result['message']);
    }

    public function step5(): Factory|View|Application
    {
        return view('installation.step5', [
            'appName' => data_get(session('installer.environment'), 'APP_NAME', env('APP_NAME', 'RestaurantPizzeria')),
            'seededAdmin' => $this->installation->seededAdmin(),
        ]);
    }

    public function saveAdmin(Request $request): RedirectResponse
    {
        $data = Validator::make($request->all(), [
            'app_name' => ['required', 'string', 'max:80'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ])->validate();

        try {
            $this->installation->createAdmin(array_merge($data, session('installer.business_settings', [])));
            session(['installer.admin_created' => true]);

            return redirect()->route('install.finish')->with('success', 'Admin account created successfully.');
        } catch (Throwable $exception) {
            return back()->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['admin' => $exception->getMessage()]);
        }
    }

    public function finish(): Factory|View|Application
    {
        return view('installation.step6');
    }

    public function finalize(): RedirectResponse
    {
        if (! session('installer.admin_created')) {
            return redirect()->route('install.admin')->withErrors(['admin' => 'Create the admin account before finalizing installation.']);
        }

        try {
            $this->environment->update(['APP_INSTALL' => 'true']);
            $this->installation->createLockFile();
            $this->installation->optimizeApplication();
            session()->forget('installer');
        } catch (Throwable $exception) {
            return back()->withErrors(['finish' => $exception->getMessage()]);
        }

        return redirect()->route('admin.auth.login')->with('success', 'Installation completed successfully.');
    }

    public function database_installation(Request $request): RedirectResponse
    {
        return $this->saveEnvironment($request);
    }

    public function system_settings(Request $request): RedirectResponse
    {
        return $this->saveAdmin($request);
    }
}
