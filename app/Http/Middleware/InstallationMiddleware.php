<?php

namespace App\Http\Middleware;

use App\Services\Installer\InstallationService;
use Closure;
use Illuminate\Http\Request;

class InstallationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (app(InstallationService::class)->isInstalled()) {
            return redirect()->route('admin.auth.login');
        }

        return $next($request);
    }
}
