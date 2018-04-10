<?php namespace App\Http\Controllers;

use App\FAQ;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdminFAQController extends Controller {

	use CRUDTrait;

	protected static $model = 'App\FAQ';
	protected static $route = 'admin/faqs'; // routes
	protected static $views = 'admin/faqs'; // view folder


	public function index()
	{
		$rows = FAQ::orderBy('question')->get();
		$route = self::$route;

		return view(self::$views . '/index', compact('rows', 'route'));
	}

}
