<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Language;
use App\Service;

class ServiceController extends Controller {

	public function index()
	{
		$services = Service::parents()->orderBy('title')->get();
		$languages = Language::notHidden()->orderBy('name')->get();

		return view('services.index', compact('services', 'languages'));
	}

	public function show($slug)
	{
		$service = Service::where('slug', $slug)->first();

		if (!$service) {
			abort(404, 'Service not found');
		}

		return view('services.show', compact('service'));
	}
}
