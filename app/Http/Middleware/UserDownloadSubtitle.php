<?php namespace App\Http\Middleware;

use App\AudioSubtitle;
use App\AudioTranscription;
use Closure;

use Illuminate\Support\Facades\Auth;
use App\Audio;
use App\AudioSlice;
use App\Order;

class UserDownloadSubtitle {

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
		$subtitle_id = $request->route()->subtitle_id;
		//$subtitle = AudioSubtitler::findOrFail($request->route()->slice_id);

		// admin
		if (Auth::check() && Auth::user()->hasRole('admin')) {
			return $next($request);
		}

		// subtitler
		$belongs_to_subtitler = AudioSubtitle::where('id', $subtitle_id)->exists();
		if ($belongs_to_subtitler) {
			return $next($request);
		}

		// client
		$belongs_to_user = AudioTranscription::where('id', $subtitle_id)->whereHas('audio', function($q) {
			$q->whereHas('order', function($q) {
				$q->whereHas('user', function($q) {
					$q->where('id', Auth::id());
				});
			});
		})->exists();
		if ($belongs_to_user) {
			return $next($request);
		}

		return response('Unauthorized.', 401);
	}

}
