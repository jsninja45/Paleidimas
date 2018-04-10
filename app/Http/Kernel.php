<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'App\Http\Middleware\VerifyCsrfToken',
		'App\Http\Middleware\SetReferrer',
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => 'App\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'App\Http\Middleware\RedirectIfAuthenticated',

		'auth.client' => 'App\Http\Middleware\AuthClient',
		'auth.transcriber' => 'App\Http\Middleware\AuthTranscriber',
		'auth.editor' => 'App\Http\Middleware\AuthEditor',
		'auth.subtitler' => 'App\Http\Middleware\AuthSubtitler',
		'auth.admin' => 'App\Http\Middleware\AuthAdmin',
		'auth.transcriber_or_editor' => 'App\Http\Middleware\AuthTranscriberOrEditor',
		'user.has_record' => 'App\Http\Middleware\HasRecord',
		'user.download_audio' => 'App\Http\Middleware\UserDownloadAudio',
		'user.download_subtitle' => 'App\Http\Middleware\UserDownloadSubtitle',
		'user.id_in_url' => 'App\Http\Middleware\UserIdInUrl',
		'user.download_partial_transcription' => 'App\Http\Middleware\UserDownloadPartialTranscription',
		'user.download_transcription' => 'App\Http\Middleware\UserDownloadAudioTranscription',
	];

}
