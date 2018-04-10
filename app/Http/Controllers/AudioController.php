<?php namespace App\Http\Controllers;

use App\Email;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Audio;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AudioController extends Controller {

	// just file, not audio
	public function deleteFile($audio_id)
	{
		$audio = Audio::findOrFail($audio_id);

		if ($audio->canClientDeleteFile()) {
			$audio->deleteFile();
		} else {
			Log::info('Audio cant be deleted', (array)$audio->id);
			abort(400, 'Audio cant be deleted');
		}

		return redirect()->back();
	}

	// rating & comment
	public function rate(Request $request, $audio_id)
	{
		$audio = Audio::findOrFail($audio_id);

		if ($audio->status !== 'finished') {
			abort(400, 'Audio is not finished');
		}

		if ($request->rating) {
			$audio->rating = $request->rating;
			$audio->rated_at = date('Y-m-d H:i:s');
			$audio->save();

			Email::editorAudioRated($audio);
			Email::adminClientRatedAudio($audio);
		}

		if ($request->comment) {
			$audio->rating_comment = $request->comment;
			$audio->rated_at = date('Y-m-d H:i:s');
			$audio->save();

			Email::editorAudioRated($audio);
			Email::adminClientRatedAudio($audio);
		}
	}

}
