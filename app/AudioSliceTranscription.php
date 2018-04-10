<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AudioSliceTranscription extends Model {

	public static function upload_folder() {

		return '?????????????';

		return storage_path('app/audio_slice_transcriptions');
	}

	public function link()
	{
		return route('partial_transcription_download', [$this->id]);
	}

	public function path()
	{
		return storage_path('app/audio_slice_transcriptions') . '/' . $this->id;
	}

}
