<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class CRUDController extends Controller {

	protected static $model = 'App\Bonus';
	protected static $route = 'admin/bonuses';
	protected static $views = 'admin/bonuses';

	public function index()
	{
		$model = self::$model;
		$rows = $model::all();

		return view('crud.index', [
			'rows' => $rows,
			'path' => self::$path,
		]);
	}

	public function create()
	{
		$example = new Example;

		return view('crud.edit', compact('example'));
	}

	public function store()
	{
		$example = new Example;
		$example->title = Input::get('title');
		$example->content = Input::get('content');
		$example->save();

		return redirect()->route('example');
	}

	public function edit($id)
	{
		$example = Example::findOrFail($id);

		return view('crud.edit', compact('example'));
	}

	public function update($id)
	{
		$example = Example::findOrFail($id);
		$example->title = Input::get('title');
		$example->content = Input::get('content');
		$example->save();

		return redirect()->route('example');
	}

	public function destroy($id)
	{
		Example::destroy($id);

		return redirect()->back();
	}

}