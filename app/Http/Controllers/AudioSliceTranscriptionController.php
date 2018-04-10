<?php namespace App\Http\Controllers;

use App\AudioSliceTranscription;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AudioSliceTranscriptionController extends Controller {

	public function show($id) // download
	{
		$transcription = AudioSliceTranscription::findOrFail($id);

		return response()->download($transcription->path(), $transcription->filename);
	}

}
