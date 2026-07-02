<?php

namespace App\Http\Middleware;

use App\Services\Installer\InstallationService;
use Closure;
use Illuminate\Http\Request;

class CheckInstallation
{
    public function handle(Request $request, Closure $next)
    {
        if (app(InstallationService::class)->isInstalled()) {
            return $next($request);
        }

        if ($request->is('install*') || $request->is('assets/installation*') || $request->is('favicon.ico')) {
            return $next($request);
        }

        if ($request->is('/') || $request->is('admin') || $request->is('admin/*')) {
            return redirect()->route('install.welcome');
        }

        return $next($request);
    }
}
