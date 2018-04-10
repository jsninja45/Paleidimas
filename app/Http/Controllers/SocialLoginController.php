<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Login;
use App\Order;
use App\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite as Socialize;
use App\User;
use Illuminate\Support\Facades\Auth;

class SocialLoginController extends Controller {

	private static $providers = [
		'facebook',
		'google',
		'twitter'
	];

	public function redirectToProvider($provider)
	{
		if (!in_array($provider, self::$providers)) {
			Log::info('wrong service provider', [$provider]);
			abort('401', 'wrong service provider');
		}

		return Socialize::with($provider)->redirect();
	}

	public function handleProviderCallback($provider)
	{
		if (!in_array($provider, self::$providers)) {
			Log::info('wrong service provider', [$provider]);
			abort('401', 'wrong service provider');
		}

		$socialize_user = Socialize::with($provider)->user();

		return self::loginOrRegister($socialize_user, $provider);
	}

	private static function loginOrRegister($socialize_user, $provider)
	{
		$user = User::where('social_login_type', $provider)->where('social_login_id', $socialize_user->getId())->first();

		//Log::info('Social user', (array)$socialize_user);

		if (!$user) {
			// register
			$user = new User;
			$user->social_login_type = $provider;
			$user->social_login_id = $socialize_user->getId();

			$email = $socialize_user->getEmail();

			if ($email) {
				if (User::where('email', $email)->exists()) {
					return redirect('/auth/login')->with('error', 'This email is already taken (' . $email . ')');
				}
				$user->email = $email;
			} else {
				$user->email = 'change_this@' . rand(1000000000, 9999999999) . '.com';
			}

			$user->save();
			$user = User::find($user->id);
		}

		// login
		$login = Login::loginUsingId($user->id);

		return $login->redirect();
	}

}
