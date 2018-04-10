<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;

class EmailExportController extends Controller {

	public function index()
	{
		return view('email_exports/index');
	}

	public function transcriberEmails()
	{
		$emails = User::notDeleted()->whereHas('roles', function($query) {
			$query->where('name', 'transcriber');
		})->lists('email');

		return view('email_exports/emails', compact('emails'));
	}

	public function editorEmails()
	{
		$emails = User::notDeleted()->whereHas('roles', function($query) {
			$query->where('name', 'editor');
		})->lists('email');

		return view('email_exports/emails', compact('emails'));
	}

	public function clientEmails()
	{
		$emails = User::canReceiveNewsletter()
			->justClient()
			->orderBy('email')
			->lists('email');

		return view('email_exports/emails', compact('emails'));
	}

	public function deletedUserEmails()
	{
		$emails = User::where('deleted', '1')->lists('email');

		return view('email_exports/emails', compact('emails'));
	}

	public function notAgreedToReceiveNewsletterEmails()
	{
		$emails = User::where('newsletter', '0')->lists('email');

		return view('email_exports/emails', compact('emails'));
	}

}
