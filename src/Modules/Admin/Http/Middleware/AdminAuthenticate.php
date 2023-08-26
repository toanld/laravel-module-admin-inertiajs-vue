<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Helpers\Constants;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && (auth()->user()->admin_type & Constants::USER_TYPE_SUPER_ADMIN) === Constants::USER_TYPE_SUPER_ADMIN) {
            return $next($request);
        }
        return redirect("/");
    }
}
