<?php namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;
use App\AudioSliceTranscription;

class UserDownloadPartialTranscription {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		//$partial_transcription_id = $request->route()->id;

		// admin
		if (Auth::check() && Auth::user()->hasRole(['transcriber', 'editor', 'admin'])) {
			return $next($request);
		}

		// error
		if ($request->ajax()) {
			return response('Unauthorized.', 401);
		} else {
			return redirect()->guest('auth/login');
		}


	}

}
