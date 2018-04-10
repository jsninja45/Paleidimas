<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Audio;

class AudioController extends Controller {

	// is this used?
	public function download($audio_id)
	{
		$audio = Audio::findOrFail($audio_id);

		if ($audio->isFileDeleted()) {
			Log::error('File not found (or was deleted)', ['audio_id' => $audio->id]);
			abort(404, 'File not found (or was deleted)');
		}

		return response()->download($audio->server_path, $audio->original_filename);
	}

}
