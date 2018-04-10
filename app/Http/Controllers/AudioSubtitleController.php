<?php namespace App\Http\Controllers;

use App\AudioSubtitle;
use App\FAQ;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AudioSubtitleController extends Controller {

	public function show($subtitle_id)
	{
		$subtitle = AudioSubtitle::findOrFail($subtitle_id);

		return response()->download($subtitle->path(), $subtitle->filename);
	}

}
