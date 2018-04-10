<?php namespace App\Http\Controllers\Auth;

use App\Affiliate;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use Illuminate\Http\Request;
use App\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;


class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	// after login where user is redirected
	protected $redirectPath = '/add-files'; // client
	protected $adminRedirectPath = '/admin'; // transcriber, editor, admin

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth, Registrar $registrar)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;

		//$this->middleware('guest', ['except' => 'getLogout']);
	}



	public function getLogin(Request $request)
	{
		Login::afterLoginRedirectToPayment(false);

		return view('auth.login');
	}

	public function postLogin(Request $request)
	{
		$login = Login::loginUsingRequest($request);
		return $login->redirect();
	}


	public function postRegister(Request $request)
	{
		$validator = $this->registrar->validator($request->all());

		if ($validator->fails())
		{
			$this->throwValidationException(
				$request, $validator
			);
		}

		$this->auth->login($this->registrar->create($request->all()));

		Login::afterLogin(Auth::user());

		return redirect($this->redirectPath());
	}

	public function checkCredentials(Request $request)
	{
		$user = User::where('email', $request->email)->first();

        if ($user && $user->password && Hash::check($request->password, $user->password)) { // check laravel algorithm
            return Response::json('success', 200);
		} else {
			return Response::json('wrong password', 401);
		}
	}

}
