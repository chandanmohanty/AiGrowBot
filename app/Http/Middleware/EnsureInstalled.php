<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureInstalled
{
    /**
     * If the installer has not been completed (marker file missing),
     * force every non-install request to the wizard.
     * If the installer HAS been completed, block any access to /install/*.
     */
    public function handle(Request $request, Closure $next)
    {
        $installed = is_file(storage_path('app/installed.lock'));
        $onInstallRoute = $request->is('install') || $request->is('install/*');

        if (! $installed && ! $onInstallRoute && ! $request->is('up')) {
            return redirect('/install');
        }

        if ($installed && $onInstallRoute) {
            abort(404);
        }

        return $next($request);
    }
}
