<?php namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\Auth;
use App\Audio;
use App\AudioSlice;
use App\Order;

class UserDownloadAudio {

	/**
	 * Handle an incoming request.
	 *
	 * @TODO needs more granular checking who can access files
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		//$error = false;
		$audio_id = $request->route()->audio_id;


		if (Auth::check()) {
			if (Auth::user()->hasRole('transcriber', 'editor', 'admin')) {
				return $next($request);
			}
		}

		$order = Order::getUnpaidOrder();
		if ($order->whereHas('audios', function($query) use ($audio_id) {
			$query->where('id', $audio_id);
		})->exists()) {
			return $next($request);
		}





/*
		// login access
		if (!Auth::check() || !Auth::user()->hasRole(['client', 'transcriber', 'editor', 'admin'])) {
			$error = true;
		}

		// user can access file
		while (true) {


			if (Auth::check()) {

				// if logged in

				// admin
				if (Auth::user()->hasRole('admin')) {
					break;
				}

				// a bit incorrect, but...
				$file_available = Audio::where('id', $audio_id)->where('status', '!=', 'finished')->exists();
				if ($file_available) {
					break;
				}

				// client
				$client_file = Audio::where('id', $audio_id)->whereHas('order', function($query) {
					$query->where('user_id', Auth::id());
				})->exists();

				// editor
				$editor_file = Audio::where('id', $audio_id)->where('editor_id', Auth::id())->exists();
				if ($editor_file) {
					break;
				}

				// transcriber
				$transcriber_file = AudioSlice::whereHas('audio', function($query) use ($audio_id) {
					$query->where('id', $audio_id)->whereHas('order', function($query) {
						$query->where('user_id', Auth::id());
					});
				})->exists();

				if (!$client_file && !$editor_file && !$transcriber_file) {
					$error = true;
				}

				break;
			} else {
				// if not logged in



			}



		}





		// error
		if ($error) {
			if ($request->ajax()) {
				return response('Unauthorized.', 401);
			} else {
				return redirect()->guest('auth/login');
			}
		}

		return $next($request);

*/
	}

}
