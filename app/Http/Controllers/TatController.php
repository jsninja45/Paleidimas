<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Tat;

class TatController extends Controller {

//	public function create()
//	{
//		$tat = new Tat;
//
//		return view('tat.edit', compact('tat'));
//	}

//	public function store(Request $request)
//	{
//		$tat = new Tat;
//		$tat->days = $request->get('days');
//		$tat->client_price_per_minute = $request->get('client_price_per_hour') / 60;
//		$tat->client_price_per_minute = $request->get('client_price_per_hour') / 60;
//		$tat->transcriber_price_per_minute = $request->get('transcriber_price_per_hour') / 60;
//		$tat->slice_duration = $request->get('slice_duration') * 60;
//		$tat->max_transcription_duration = $request->get('max_transcription_duration') * 3600;
//		$tat->save();
//
//		return redirect()->route('admin_config');
//	}

	public function edit($id)
	{
		$tat = Tat::findOrFail($id);

		return view('tat.edit', compact('tat'));
	}

	public function update(Request $request, $id)
	{
		$tat = Tat::findOrFail($id);
		$tat->days = $request->get('days');
		$tat->client_price_per_minute = $request->get('client_price_per_hour') / 60;
		$tat->editor_price_per_minute = $request->get('editor_price_per_hour') / 60;
		$tat->transcriber_price_per_minute = $request->get('transcriber_price_per_hour') / 60;
		$tat->slice_duration = $request->get('slice_duration') * 60;
		$tat->max_transcription_duration = $request->get('max_transcription_duration') * 3600;
		$tat->save();

		return redirect()->route('admin_config');
	}

//	public function destroy($id)
//	{
//		Tat::destroy($id);
//
//		return redirect()->back();
//	}

}
