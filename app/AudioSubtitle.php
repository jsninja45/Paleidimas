<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class AudioSubtitle extends Model {


	// ---------------------------------------- relationships ----------------------------------------------------------

	public function audio()
	{
		return $this->belongsTo('App\Audio');
	}

	// ----------------------------------------- methods ---------------------------------------------------------------

	public function path() {
		return storage_path() . '/app/audio_subtitles/' . $this->id;
	}

	public function link()
	{
		return $this->download();
	}

	public function download()
	{
		if (!$this->isFileDeleted()) {
			// file
			return route('subtitles.download', [$this->id]);
		} else {
			Log::info('File not found (or was deleted) 2', ['audio_id' => $this->id]);
			abort(404, 'File not found (or was deleted) 2');
		}
	}

	public function isFileDeleted()
	{
		return !File::exists($this->path());
	}

	// ----------------------------------------- scopes ---------------------------------------------------------------

	public function scopeFilter($q, Request $request)
	{
		if ($request->has('user_id')) {
			$q->where('user_id', $request->user_id);
		}

	}

}
