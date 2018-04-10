<?php namespace App\Http\Controllers;

use App\Audio;
use App\AudioTranscription;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AudioTranscriptionController extends Controller {

	public function show($transcription_id)
	{
		$transcription = AudioTranscription::findOrFail($transcription_id);

        $client_id = Audio::where('id', $transcription->audio_id)->first()->order->client_id;

		if (!Auth::check()) {
			return redirect('auth/login');
		} else if (Auth::user()->id != $client_id && !Auth::user()->hasRole(['admin'])) {
			return abort(404);
		}

		return response()->download($transcription->path(), $transcription->filename);
	}

}
