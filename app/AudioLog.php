<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AudioLog extends Model {

	public static function log($user_id, $audio_id, $action)
	{
		$allowed = [
			'take',
			'cancel',
			'finish',
			'deadline_miss',
		];

		if (!in_array($action, $allowed)) {
			Log::error("Status doesn't exist ({$action})");
			abort('400', "Status doesn't exist ({$action})");
		}

		$log = new AudioLog();
		$log->audio_id = $audio_id;
		$log->action = $action;
		$log->user_id = $user_id;
		$log->save();
	}

}
