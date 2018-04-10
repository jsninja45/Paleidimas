<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Tat;
use App\Timestamping;
use App\UserPricePerMinute;
use Illuminate\Http\Request;
use App\User;
use App\Language;
use App\Role;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller {

	public function edit($id)
	{
		$user = User::findOrFail($id);
		$languages = Language::all();
		$roles = Role::all();

		$tats = Tat::all();
		$timestampings = Timestamping::findOrFail([1, 2]); // no price and with price (2, 3 have the same price)

		return view('admin.users.edit', compact('user', 'languages', 'roles', 'tats', 'timestampings'));
	}

	public function update(Request $request, $id)
	{
		$user = User::findOrFail($id);
		$user->job_limit = $request->get('job_limit');
		$user->comment = $request->comment;
		$user->save();

		$user->roles()->sync((array)$request->get('roles'));
		$user->languages()->sync((array)$request->get('languages'));

		$user_price = UserPricePerMinute::where('user_id', $user->id)->firstOrFail();
		$input = self::input($request->all());
		$user_price->fill($input);
		$user_price->enabled = (int)$request->enable_custom_rates;
		$user_price->save();

		return redirect()->back();
	}

	private static function input($input)
	{
		$price = new UserPricePerMinute();
		$fillable = $price->getFillable();

		foreach ($fillable as $column) {
			$input[$column] = $input[$column] / 60; // convert price per hour to price per minute
		}

		return $input;
	}

//	public function editPayPalEmail()
//	{
//		$user = Auth::user();
//
//		return view('admin.users.edit_paypal_email', compact('user'));
//	}
//
//	public function updatePayPalEmail(Request $request)
//	{
//		$user = Auth::user();
//		$user->paypal_email = $request->paypal_email;
//		$user->save();
//
//		return redirect()->route('worker_stats', ['user_id' => $user->id]);
//	}

	public function undelete($user_id)
	{
		$user = User::findOrFail($user_id);
		if ($user->deleted) {
			$user->deleted = 0;
			$user->save();

			return back();
		} else {
			die('error: user is not deleted');
		}
	}

}
