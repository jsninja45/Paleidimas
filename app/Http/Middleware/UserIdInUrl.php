<?php namespace App\Http\Middleware;

use App\AudioSlice;
use Closure;

use Illuminate\Support\Facades\Auth;

class UserIdInUrl {

	/**
	 * Users id that is in url can only open this url (and admin)
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Auth::id() == $request->user_id || Auth::user()->hasRole('admin')) {
			return $next($request);
		}

		if ($request->ajax())
		{
			return response('Unauthorized.', 401);
		}
		else
		{
			return redirect()->guest('auth/login');
		}
	}

}
