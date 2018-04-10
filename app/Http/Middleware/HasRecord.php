<?php namespace App\Http\Middleware;

use App\AudioSlice;
use Closure;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class HasRecord {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Auth::check() && Auth::user()->hasRole('admin')) {
			return $next($request);
		}

		$model = $request->segment(1);
		$id = $request->segment(2);

		if ($model === 'audio_slices') {
			Auth::user()->audioSlices()->findOrFail($id);
		} elseif ($model === 'audios' && $request->segment(3) === 'upload_file') {
			Auth::user()->editorAudios()->findOrFail($id);
		} elseif ($model === 'audios' && $request->segment(3) === 'upload_subtitle') {
			Auth::user()->subtitlerAudios()->findOrFail($id);
		} elseif ($model === 'audios') {
			Auth::user()->userAudios()->findOrFail($request->audio_id);
		} elseif (($request->segment(1) === 'users' && $request->segment(3) === 'orders') || ($request->segment(1) === 'upload' && $request->segment(2) === 'complete')) {
			$order = Auth::user()->orders()->find($request->order_id);
			if (!$order) {
				return abort(401, 'User has no access');
			}
		} elseif ($request->segment(1) === 'download') {
			if (!Auth::check()) {
				$order = \App\Order::getUnpaidOrder();
				if ($order) {
					$order->audios()->where('audios.id', $request->audio_id)->firstOrFail();
				} else {
					Log::error('User has no access to edit this record');
					return abort(401, 'User has no access to edit this record');
				}
				return $next($request);
			}
			if (Auth::check()) {
				if (Auth::user()->hasRole('admin', 'editor', 'transcriber')) {
					return $next($request);
				}
				Auth::user()->userAudios()->findOrFail($request->audio_id);
				return $next($request);
			}
		} else {
			Log::error('User has no access to edit this record');
			return abort(401, 'User has no access to edit this record');
		}

		return $next($request);
	}

}
