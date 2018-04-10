<?php namespace App\Http\Controllers;

use App\Config;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class NoRatingDelayController extends Controller {

	public function edit() {
		$config = Config::get('slice_no_rating_delay', true);

		return view('admin.no_rating_delays.edit', compact('config'));
	}

	public function update(Request $request) {
		Config::set('slice_no_rating_delay', $request->value, $request->comment);

		return redirect()->route('admin_config');
	}

}
