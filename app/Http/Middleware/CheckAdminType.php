<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAdminType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $type): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect('admin/login');
        }

        $user = Auth::guard('admin')->user();

        if ($user->type === $type) {
            return $next($request);
        }
        // dd($user->type);

        if ($user->type === 'vendor') {
            abort(403, 'Vendors are not allowed to access this section.');
        }

        abort(403, 'Unauthorized access.');
    }
}
