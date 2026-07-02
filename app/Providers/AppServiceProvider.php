<?php

namespace App\Providers;

use App\Model\BusinessSetting;
use App\Model\Conversation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

ini_set('memory_limit', '-1');

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $timezone = BusinessSetting::where(['key' => 'time_zone'])->first();
            if (isset($timezone)) {
                config(['app.timezone' => $timezone->value]);
                date_default_timezone_set($timezone->value);
            }
        }catch(\Exception $exception){}

        View::composer(['layouts.admin.app', 'layouts.admin.partials.*'], function ($view) {
            $settings = BusinessSetting::whereIn('key', ['fav_icon', 'logo', 'footer_text', 'language'])
                ->pluck('value', 'key');
            $languageSettings = json_decode($settings['language'] ?? '[]', true);
            $languageSettings = is_array($languageSettings) ? $languageSettings : [];

            $view->with([
                'adminLayoutSettings' => $settings,
                'adminLanguageSettings' => $languageSettings,
                'adminUnreadConversationCount' => Conversation::where('checked', 0)
                    ->distinct('user_id')
                    ->count('user_id'),
            ]);
        });

        View::composer(['layouts.branch.app', 'layouts.branch.partials.*'], function ($view) {
            $settings = BusinessSetting::whereIn('key', ['fav_icon', 'logo', 'footer_text', 'language'])
                ->pluck('value', 'key');
            $languageSettings = json_decode($settings['language'] ?? '[]', true);
            $languageSettings = is_array($languageSettings) ? $languageSettings : [];

            $view->with([
                'branchLayoutSettings' => $settings,
                'branchLanguageSettings' => $languageSettings,
            ]);
        });

        View::composer('welcome', function ($view) {
            $paymentSettingKeys = [
                'cash_on_delivery',
                'digital_payment',
                'ssl_commerz_payment',
                'razor_pay',
                'paypal',
                'stripe',
            ];

            $view->with('paymentSettings', BusinessSetting::whereIn('key', $paymentSettingKeys)
                ->pluck('value', 'key')
                ->map(fn($value) => json_decode($value, true) ?: $value));
        });

        Paginator::useBootstrap();
    }
}
