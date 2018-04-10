<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class RatingDelayController extends Controller {

	use CRUDTrait;


	protected static $model = 'App\RatingDelay';
	protected static $route = 'rating_delays';
	protected static $views = 'rating_delays'; // view folder


	public function store(Request $request)
	{
		$model = self::$model;
		$row = new $model;
		$row->fill($request->all());
		$row->delay = round($request->delay * 60);
		$row->save();

		return redirect()->route('admin_config');
	}

	public function update(Request $request, $id)
	{
		$model = self::$model;
		$row = $model::findOrFail($id);
		$row->fill($request->all());
		$row->delay = round($request->delay * 60);
		$row->save();

		return redirect()->route('admin_config');
	}

}
