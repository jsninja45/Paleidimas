<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Service;
use Illuminate\Http\Request;

class AdminServiceController extends Controller {

	use CRUDTrait;

	protected static $model = 'App\Service';
	protected static $route = 'admin/services'; // routes
	protected static $views = 'admin.services'; // view folder

	public function store(Request $request)
	{
		$service = new Service();
		return $service->storeOrUpdate($request);
	}

	public function update(Request $request, $article_id)
	{
		$service = Service::findOrFail($article_id);
		return $service->storeOrUpdate($request);
	}

}
