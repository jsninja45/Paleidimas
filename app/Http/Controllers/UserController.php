<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\AudioLog;
use App\AudioSliceLog;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

	public function show($id)
	{
		$user = User::findOrFail($id);

		return view('users.show', compact('user'));
	}

	public function edit($id)
	{
		$user = User::findOrFail($id);

		return view('users.edit', compact('user'));
	}

	public function update($id)
	{
		$rules = [
			'paypal_email' => 'email',
		];

		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator);
		}

		$user = User::findOrFail($id);
		$user->paypal_email = Input::get('paypal_email');
		$user->new_job_email = Input::get('new_job_email');
//		$user->fill(Input::all());
//		if (Input::has('password')) {
//			$user->password = Input::get('password');
//		}
		$user->save();

		return redirect()->route('profile', [$user->id]);
	}

	public function takeAudioSliceJob($slice_id) // to user
	{
		$user = Auth::user();
		$user->takeAudioSlice($slice_id);

		//return redirect()->route('in_progress_transcription_jobs');
		return redirect()->back();
	}

	public function cancelAudioSliceJob($slice_id)
	{
		$user = Auth::user();
		$user->cancelAudioSliceJob($slice_id);

//		return redirect()->route('available_transcription_jobs');
		return redirect()->back();
	}

	public function finishAudioSliceJob($slice_id)
	{
		$user = Auth::user();
		$user->finishAudioSliceJob($slice_id);

//		return redirect()->route('finished_transcription_jobs');
		return redirect()->back();
	}

	public function takeAudioJob($slice_id) // to user
	{
		$user = Auth::user();
		$user->takeAudioJob($slice_id);

//		return redirect()->route('in_progress_editing_jobs');
		return redirect()->back();
	}

	public function cancelAudioJob($slice_id)
	{
		$user = Auth::user();
		$user->cancelAudioJob($slice_id);

//		return redirect()->route('available_for_editing_jobs');
		return redirect()->back();
	}

	public function finishAudioJob($slice_id)
	{
		$user = Auth::user();
		$user->finishAudioJob($slice_id);

//		return redirect()->route('finished_editing_jobs');
		return redirect()->back();
	}

	public function takeSubtitlingJob($audio_id) // to user
	{
		$user = Auth::user();
		$user->takeSubtitlingJob($audio_id);

		return redirect()->back();
	}

	public function cancelSubtitlingJob($audio_id)
	{
		$user = Auth::user();
		$user->cancelSubtitlingJob($audio_id);

		return redirect()->back();
	}

	public function finishSubtitlingJob($audio_id)
	{
		$user = Auth::user();
		$user->finishSubtitlingJob($audio_id);

		return redirect()->back();
	}

	public function settings($user_id)
	{
		$user = User::findOrFail($user_id);

		return view('users.settings', compact('user'));
	}

	public function changeEmail(Request $request, $user_id)
	{
		$user = User::findOrFail($user_id);

		// check if user entered correct password
		if (!Hash::check($request->password, $user->password))
		{
			abort('401', 'Wrong password');
		}

		$input = Input::all();
		$input['email'] = Input::get('new_email');
		$validator = Validator::make($input, User::$rules);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator);
		}

		$user->email = $input['email'];
		$user->save();

		return redirect()->back();
	}


	public function changePassword(Request $request, $user_id)
	{
		$user = User::findOrFail($user_id);

		// check if user entered correct password
		if (!Hash::check($request->password, $user->password))
		{
			abort('401', 'Wrong password');
		}

		$input = Input::all();
		$input['password'] = Input::get('new_password');
		$rules['password'] = User::$rules['password'];
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator);
		}

		$user->password = $input['password'];
		$user->save();

		return redirect()->back();
	}

	public function setNewsletter($user_id)
	{
		$user = User::findOrFail($user_id);
		$user->newsletter = Input::get('newsletter');
		$user->save();
	}

	// send emails when audio is finished
	public function emailSettings(Request $request, $user_id)
	{
		if ($request->has('finished-audio-email')) {
			$user = User::findOrFail($user_id);
			$user->finished_audio_email = $request->get('finished-audio-email');
			$user->save();
		}
	}

	public function deleteAccount($user_id) {
		$user = User::findOrFail($user_id);
		$user->deleted = 1;
		$user->save();

		Auth::logout();

		return redirect('/');
	}

	public function addSecondEmail(Request $request, $user_id)
	{
		$second_email = $request->second_email;

		$validator = Validator::make(
			array(
				'second_email' => $second_email,
			),
			array(
				'second_email' => 'required|email',
			)
		);

		if ($validator->fails())
		{
			Log::info('Bad second email entered');
			abort('400', 'Bad email');
		}

		$user = User::findOrFail($user_id);
		$user->second_email = $second_email;
		$user->save();
	}

	public function deleteSecondEmail($user_id)
	{
		$user = User::findOrFail($user_id);
		$user->second_email = null;
		$user->save();
	}

}
