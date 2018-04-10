<?php namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;

class SetReferrer {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (!Auth::check() && $request->has('x') && !isset($_COOKIE['referrer'])) {
            setcookie('referrer', '?x=' . $request->get('x'), time() + 3600 * 24 * 30);
        }

		return $next($request);
	}

}
