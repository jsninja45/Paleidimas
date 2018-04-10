<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminPageController extends Controller {

	public function index()
	{
		if (!Auth::user()->hasRole('admin', 'editor', 'transcriber')) {
			return redirect('/');
		}

		return view('admin.home');
	}

}
