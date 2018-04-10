<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\AdminNotification;

class AudioSliceLog extends Model {

	// --------------------------------------- scopes ------------------------------------------------------------------
	public function scopeTaken($query)
	{
		$query->where('action', 'take');
	}

	public function scopeCanceled($query)
	{
		$query->where('action', 'cancel');
	}

	public function scopeFinished($query)
	{
		$query->where('action', 'finish');
	}

	public function scopeMissedDeadline($query)
	{
		$query->where('action', 'deadline_miss');
	}




	// --------------------------------------- methods -----------------------------------------------------------------


	public static function log($user_id, $slice_id, $action)
	{
		$allowed = [
			'take',
			'cancel',
			'finish',
			'deadline_miss',
		];

		if (!in_array($action, $allowed)) {
			abort('400', "Status doesn't exist ({$action}");
		}

		$log = new AudioSliceLog();
		$log->slice_id = $slice_id;
		$log->action = $action;
		$log->user_id = $user_id;
		$log->save();

		// notify admin
		if ($action === 'cancel') {
			$content = 'User (' . User::findOrFail($user_id)->email . ') has canceled transcription job (id: ' . $slice_id . ')';
			AdminNotification::add($content);
		}

	}

}
