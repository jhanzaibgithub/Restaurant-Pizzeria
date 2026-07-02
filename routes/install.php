<?php

use Illuminate\Support\Facades\Route;

Route::prefix('install')
    ->middleware(['web', 'installation-check'])
    ->as('install.')
    ->group(function () {
        Route::get('/', 'InstallController@step0')->name('welcome');
        Route::get('/requirements', 'InstallController@step1')->name('requirements');
        Route::get('/environment', 'InstallController@step2')->name('environment');
        Route::post('/environment', 'InstallController@saveEnvironment')->name('environment.save');
        Route::get('/database', 'InstallController@step3')->name('database');
        Route::post('/database/test', 'InstallController@testDatabase')->name('database.test');
        Route::get('/migrations', 'InstallController@step4')->name('migrations');
        Route::post('/migrations/run', 'InstallController@runMigrations')->name('migrations.run');
        Route::get('/admin', 'InstallController@step5')->name('admin');
        Route::post('/admin', 'InstallController@saveAdmin')->name('admin.save');
        Route::get('/finish', 'InstallController@finish')->name('finish');
        Route::post('/finish', 'InstallController@finalize')->name('finalize');
    });

// Backward-compatible installer route names used by older documentation.
Route::middleware(['web', 'installation-check'])->group(function () {
    Route::redirect('/step1', '/install/requirements')->name('step1');
    Route::redirect('/step2', '/install/environment')->name('step2');
    Route::redirect('/step3', '/install/database')->name('step3');
    Route::redirect('/step4', '/install/migrations')->name('step4');
    Route::redirect('/step5', '/install/admin')->name('step5');
    Route::redirect('/step6', '/install/finish')->name('step6');
    Route::redirect('/install/start', '/install')->name('step0');
});
