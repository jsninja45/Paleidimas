<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AudioTranscription extends Model {

	public function link()
	{
		return route('transcription_download', ['transcription_id' => $this->id]);
	}

	public function path()
	{
		return storage_path('app/audio_transcriptions') . '/' . $this->id;
	}

}
