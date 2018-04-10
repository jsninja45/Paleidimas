<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller {

	public function contacts()
	{
		return view('pages.contacts');
	}

	public function sendEmail(Request $request)
	{
		$rules = [
			'email' => 'required|email',
			'name' => 'required',
			'content' => 'required',
		];

		$input = $request->all();

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator->errors());
		}

		Mail::send('emails.pages.contact_us', $input, function($message) use ($input)
		{
			$message->to(env('MAIL_EMAIL'));
			$message->replyTo($input['email']);
		});

		return redirect()->back()->with('success', 'Your message was sent');
	}

}
