<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Timestamping;
use Illuminate\Http\Request;

class TimestampingController extends Controller {

	public function create()
	{
		$timestamping = new Timestamping();

		return view('timestamping.edit', compact('timestamping'));
	}

	public function store(Request $request)
	{
		$timestamping = new Timestamping;
		$input = self::input($request->all());
		$timestamping->fill($input);
		$timestamping->save();

		return redirect()->route('admin_config');
	}

	public function edit($id)
	{
		$timestamping = Timestamping::findOrFail($id);

		return view('timestamping.edit', compact('timestamping'));
	}

	public function update(Request $request, $id)
	{
		$timestamping = Timestamping::findOrFail($id);
		$input = self::input($request->all());
		$timestamping->fill($input);
		$timestamping->save();

		return redirect()->route('admin_config');
	}

	public function destroy($id)
	{
		Timestamping::destroy($id);

		return redirect()->back();
	}

	private static function input($input) {
		$input['client_price_per_minute'] = $input['client_price_per_hour'] / 60;
		$input['editor_price_per_minute'] = $input['editor_price_per_hour'] / 60;

		return $input;
	}

}
