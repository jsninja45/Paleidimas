<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

trait CRUDTrait {

//	protected static $model = 'App\Example';
//	protected static $route = 'admin/coupons'; // routes
//	protected static $views = 'rating_delays'; // view folder


	public function index()
	{
		$model = self::$model;
		$rows = $model::all();

		return view(self::$views . '.index', [
			'rows' => $rows,
			'route' => self::$route,
		]);
	}

	public function create()
	{
		$model = self::$model;
		$row = new $model;

		return view(self::$views . '.edit', [
			'row' => $row,
			'route' => self::$route,
		]);
	}

	public function store(Request $request)
	{
		$model = self::$model;
		$row = new $model;
		$row->fill($request->all());
		$row->save();

		return redirect(self::$route);
	}

	public function edit($id)
	{
		$model = self::$model;
		$row = $model::findOrFail($id);

		return view(self::$views . '.edit', [
			'row' => $row,
			'route' => self::$route,
		]);
	}

	public function update(Request $request, $id)
	{
		$model = self::$model;
		$row = $model::findOrFail($id);
		$row->fill($request->all());
		$row->save();

		return redirect(self::$route);
	}

	public function destroy($id)
	{
		$model = self::$model;
		$model::destroy($id);

		return redirect()->back();
	}

}