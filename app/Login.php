<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\Session;

/*

Usage 1:

$login = new Login();
$status = $login->login($request);
return $login->redirect();


Usage 2:

$login = new Login();
$status = $login->login($request);
if (!$status) { // true or false depending if user is logged in or error happened
	return $login->redirect();
} else {
	return route('payment');
}
*/

/**
 *
 *
 * Usage
 *

 *
 * Class Login
 * @package App
 */
class Login extends Model {

	use AuthenticatesAndRegistersUsers;

	protected $redirectPath = '/'; // client
	protected $adminRedirectPath = '/admin'; // transcriber, editor, admin

	protected $redirect;
	protected $errors;
	protected $input;

	public function __construct()
	{
		$this->redirect = route('upload');
		$this->errors = null;
	}

	public function errors()
	{
		return $this->errors;
	}


	public static function afterLoginRedirectToPayment($redirect = true)
	{
		if ($redirect) {
			Session::put('after_login_redirect_to_payment', true);
		} else {
			Session::pull('after_login_redirect_to_payment'); // destroy
		}

	}

	/**
	 * Redirect response where to redirect user after login (on success and on error)
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function redirect() {
		if ($this->errors) {
			// error
			return redirect($this->loginPath())->withInput($this->input)->withErrors($this->errors);
		} else {
			// ok
			return redirect()->to($this->redirect); // default
		}
	}

	/**
	 * Login user
	 * After that you can get errors and default redirect path
	 *
	 * @param $request POST data (input)
	 * @return bool
	 */
	public static function loginUsingRequest($request)
	{
		$that = new self;

		$order = Order::getUnpaidOrder();

		$that->input = $request->only('email', 'password');


		Auth::validate($request->only('email', 'password'), [
			'email' => 'required', 'password' => 'required',
		]);

		$field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
		// deleted?
		$user = User::where($field, $request->get('email'))->first();
		if ($user && $user->deleted) {
			$that->errors = ['email' => 'This account is deleted.'];
			$that->redirect = 'auth/login';
//			$this->redirect = redirect()->back()
//				->withInput($request->only('email', 'remember'))
//				->withErrors($this->errors);
			return $that;
		}

        // reset drupal 7 password to new algorithm
        if ($user && $user->old_password && user_check_password($request->password, $user->old_password)) {
            $user->password = $request->password;
            $user->old_password = NULL;
            $user->save();
        }


        $credentials = [$field => $request->email, 'password' => $request->password];
		if (Auth::attempt($credentials, $request->has('remember')))
		{
			Upload::assignOrderToUser($order);

			$that = $that->setRedirectPathAfterLogin();

			self::afterLogin(Auth::user());

			return $that;
		} else {
			$that->errors = ['email' => $that->getFailedLoginMessage()];
			$that->redirect = 'auth/login';
//		$this->redirect = redirect($this->loginPath())
//			->withInput($request->only('email', 'remember'))
//			->withErrors($this->errors);
			return $that;
		}
	}

	public static function loginUsingId($user_id)
	{
		$order = Order::getUnpaidOrder();

		$that = new self;

		// deleted
		$user = User::findOrFail($user_id);
		if ($user->deleted) {
			$that->errors = ['email' => 'This account is deleted.'];
			return $that;
		}

		Auth::loginUsingId($user_id);
		Upload::assignOrderToUser($order);

		$that = $that->setRedirectPathAfterLogin();

		self::afterLogin($user);

		return $that;
	}

	public static function afterLogin($user)
	{
		// affiliate
		Affiliate::userLoggedIn($user);

		// user ip
		$user->ip = $_SERVER['REMOTE_ADDR'];
		$user->save();
	}

	private function setRedirectPathAfterLogin()
	{
		// ready to pay, 1,2,3,4 steps
		if (Session::pull('after_login_redirect_to_payment') && Order::getUnpaidOrder()->audios->count()) {
			$this->redirect = route('payment');
			return $this;
		}

		// admin panel
		if (Auth::user()->hasRole(['editor', 'transcriber', 'admin', 'subtitler'])) {
			$this->redirect = $this->redirectToAdminPanel(Auth::user());
			return $this;
		}

		// user has paid orders
		if (Auth::user()->orders()->paid()->exists()) {
			$this->redirect = route('user_orders', [Auth::id()]);
			return $this;
		}

		$this->redirect = route('upload');

		return $this;
	}

	private function redirectToAdminPanel($user) {

		// first login (editor and transcriber)
		if ($user->created_at === $user->update_at) {
			$user->touch();

			if ($user->hasRole(['transcriber', 'editor', 'subtitler'])) {
				return route('profile', [$user->id]);
			}
		}

		if ($user->hasRole('admin')) {
			return $this->adminRedirectPath; // default
		}

		if ($user->hasRole('subtitler')) {
			return route('subtitling_jobs.available');
		}

		if ($user->hasRole('editor')) {
			return route('available_for_editing_jobs');
		}

		if ($user->hasRole('transcriber')) {
			return route('available_transcription_jobs');
		}

		return $this->adminRedirectPath; // default
	}



}
