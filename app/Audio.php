<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class Audio extends Model {

	protected $table = 'audios';

	protected $fillable = [
		'original_filename',
		'price',
	];

	protected $visible = [
		'id',
		'original_filename',
		'size',
		'duration_for_humans',
		'original_duration',
		'duration',
		'from',
		'till',
		'comment',
		'from_human',
		'till_human',
		'download',
	];

	protected $appends = [
		'duration_for_humans',
		'duration',
		'from_human',
		'till_human',
		'download',
	];

	public static function boot()
	{
		parent::boot();

		Audio::saving(function($audio)
		{
			// validating data
//			if ($audio->till == 0 || $audio->till <= $audio->from) {
//				die('audio info not correct');
//			}

			$audio->original_duration = round($audio->original_duration);

			// recalculate editor's pay
			//$audio->editor_price = $audio->editorPrice();
		});

		Audio::deleting(function($audio)
		{
			File::delete($audio->path);

			deleteAll($audio->logs);
			deleteAll($audio->slices);
			deleteAll($audio->transcriptions);
		});
	}

	// ----------------------------------------- relationships ---------------------------------------------------------
	public function slices()
	{
		return $this->hasMany('App\AudioSlice');
	}

	public function transcription() {
		return $this->hasOne('App\AudioTranscription')->orderBy('created_at', 'desc');
	}

	public function transcriptions()
	{
		return $this->hasMany('App\AudioTranscription');
	}

	public function subtitles()
	{
		return $this->hasMany('App\AudioSubtitle');
	}

	public function subtitle()
	{
		return $this->hasOne('App\AudioSubtitle')->orderBy('created_at', 'desc');
	}

	public function order() {
		return $this->belongsTo('App\Order');
	}

	public function editor()
	{
		return $this->belongsTo('App\User', 'editor_id');
	}

	public function subtitler()
	{
		return $this->belongsTo('App\User', 'subtitler_id');
	}

	public function logs()
	{
		return $this->hasMany('App\AudioLog');
	}

	// ------------------------------------------ scope ----------------------------------------------------------------
	public function scopeAvailableForEditing($query)
	{
		$query->where('status', 'available_for_editing');
	}

	public function scopeAvailableForUser($q, $user) { // to edit
		$user_language_ids = $user->languages->lists('id');
		$q->availableForEditing()->whereHas('order', function($q) use ($user_language_ids) {
			$q->whereHas('language', function($q) use ($user_language_ids) {
				$q->whereIn('id', $user_language_ids);
			});
		});
	}

	public function scopeInProgress($query)
	{
		die('you meant inEditing?');
	}

	public function scopeInEditing($query)
	{
		$query->where('status', 'in_editing');
	}

	public function scopeFinishedEditing($q)
	{
		$statuses = ['available_for_subtitling', 'in_subtitling', 'finished'];

		$q->whereIn('status', $statuses)->orderBy('id', 'desc');
	}

	public function scopeLateFirst($query)
	{
		$query
			->join('orders as orders', 'audios.order_id', '=', 'orders.id')
			->orderBy('orders.deadline_at', 'asc')
			->select('audios.*');    // just to avoid fetching anything from joined table
	}

	public function scopeFinishedOnTime($query)
	{
		$query->finished()->whereRaw('finished_at <= editor_deadline_at');
	}

	public function scopeLate($q)
	{
		$q
			->join('orders as orders2', 'audios.order_id', '=', 'orders2.id')
			->select('audios.*')    // just to avoid fetching anything from joined table
			->whereNotNull('orders2.deadline_at')->where('audios.status', '!=', 'finished')->where('orders2.deadline_at', '<', date('Y-m-d H:i:s'));
	}

	public function scopeFilter($q, Request $request)
	{
		if ($request->has('order_id')) {
			$q->where('order_id', $request->order_id);
		}
		if ($request->has('editor_id')) {
			$q->where('editor_id', $request->editor_id);
		}
        if ($request->has('paid')) {
            $q->where('status', '!=', 'unpaid');
        }
		if ($request->has('audio_id')) {
			$q->where('audios.id', $request->audio_id);
		}
	}

	public function scopeAvailableForSubtitling($q, $user = null)
	{
		$q->where('status', 'available_for_subtitling');

		// check languages
		if ($user) {
			$user_language_ids = $user->languages->lists('id');
			$q->whereHas('order', function($q) use ($user_language_ids) {
				$q->whereHas('language', function($q) use ($user_language_ids) {
					$q->whereIn('id', $user_language_ids);
				});
			});
		}
	}

	public function scopeInSubtitling($q)
	{
		$q->where('status', 'in_subtitling');
	}

	public function scopeFinished($query)
	{
		$query->where('status', 'finished');
	}

	public function scopeEditorMissedDeadline($q)
	{
		$q->inEditing()->where('editor_deadline_at', '<', date('Y-m-d H:i:s'));
	}

	// ----------------------------------------- attributes ------------------------------------------------------------

	public function getDurationAttribute() {
		return $this->till - $this->from;
	}

	public function getPathAttribute() {
		return storage_path() . '/app/audios/' . $this->id;
	}

	/**
	 * @deprecated use $audio->path
	 * @return string
	 */
	public function getServerPathAttribute() {
		return $this->path;
	}

	public function getFileExtensionAttribute() {
		$parts = explode('.', $this->original_filename);
		$extension = end($parts);

		return $extension;
	}

	public function getTimeLeftAttribute()
	{
//		$tat_days = $this->tat->days;
//		$order_date = $this->order->paid_at;
//
//		$deadline_date = $order_date->addDays($tat_days);

		$deadline_date = $this->order->deadline_at;

		return timeLeftFullWithoutSeconds(leftSeconds($deadline_date));
	}

	public function getForHumansFromAttribute()
	{
		return secondsToTime($this->from);
	}

	public function getForHumansTillAttribute()
	{
		return secondsToTime($this->till);
	}

	public function getDurationForHumansAttribute()
	{
		return duration($this->duration);
	}

	public function getFromHumanAttribute()
	{
		return secondsToTime($this->from);
	}

	public function getTillHumanAttribute()
	{
		return secondsToTime($this->till);
	}

	public function getDownloadAttribute()
	{
		return $this->download();
	}

    public function setEditorPriceOverrideAttribute($value)
    {
        if (!$value) {
            $this->attributes['editor_price_override'] = null;
        } else {
            $this->attributes['editor_price_override'] = $value;
        }
    }

    // ------------------------------------------ other methods --------------------------------------------------------

	public function isAvailableForEditing()
	{
		return (bool)Audio::availableForEditing()->where('id', $this->id)->count();
	}

	public function isAvailableForSubtitling()
	{
		return (bool)Audio::availableForSubtitling()->where('id', $this->id)->count();
	}

	public function canUserTakeJob($user, $check_job_count = true) // editing
	{
		// check if available for user
		if (!Audio::where('id', $this->id)->availableForUser($user)->exists()) {
			return false;
		}

		// job count
		if ($check_job_count) {
			if ($user->inProgressJobCount() >= $user->job_limit) {
				return false;
			}
		}

		return true;
	}

	public function canUserTakeJobForSubtitling($user) // editing
	{
		// check if available for user
		if (!Audio::where('id', $this->id)->availableForSubtitling($user)->exists()) {
			return false;
		}

		// job count
		if ($user->inProgressJobCount() >= $user->job_limit) {
			return false;
		}

		return true;
	}

	// file not deleted and passed 7 days when job was finished
	public function canClientDeleteFile()
	{
		if ($this->isFileDeleted()) {
			return false;
		}

		$seven_days_after_finish = date('Y-m-d H:i:s', strtotime($this->finished_at . ' +7 day'));
		if ($this->finished_at !== null && $seven_days_after_finish < date('Y-m-d H:i:s')) {
			return true;
		}

		return false;
	}

	public function isFileDeleted()
	{
		if ($this->url) { // youtube, vimeo
			return false;
		}

		return !File::exists($this->path);
	}

	public function deleteFile()
	{
		File::delete($this->path);

		$this->url = null;
		$this->save();
	}

	public function slice()
	{
		$audio = $this;

		$slice_count = ceil($audio->duration / $audio->order->tat->slice_duration);

		for ($i = 0; $i < $slice_count; $i++) {
			$from = $i * $audio->order->tat->slice_duration + (int)$audio->from;
			$till = ($i + 1) * $audio->order->tat->slice_duration + (int)$audio->from;
			if ($till > $audio->till) {
				$till = $audio->till;
			}

			// make last slice 30 seconds or longer
			if ($i == ($slice_count - 1)) { // last slice
				$duration = $till - $from;

				if ($duration < 30 && isset($slice) && $slice->id) {
					$slice->till = $till;
					$slice->save();
					break;
				}
			}

			$slice = new AudioSlice();
			$slice->audio_id = $this->id;
			$slice->from = $from;
			$slice->till = $till;
			$slice->save();
		}
	}






	/**
	 * Create audio from file stored in storage/app/audios
	 *
	 * @param $name Filename
	 */
	public static function createAudio($name) {
//		if (!Request::file($name)->isValid()) {
//			abort(400, 'Error with file');
//		}
//
//		$mime = File::mimeType($path);


//		$audio = new Audio;
//		$audio->order_id = 0;
//		$audio->original_filename = $name;
//		$audio->duration = Audio::getDuration($name);
//		$audio->save();

//		$slices_count = ceil($length / Audio::$slice_duration);
//		for ($i = 0; $i < $slices_count; $i++) {
//		    $audio_part = new AudioPart;
//			$audio_part->audio_id = $audio->id;
//			$audio_part->save();
//		}


	}

	// file
	// getID3 - another library for getting file length
	public static function getDuration($name) {

        if (env('FAKE_FILE_DURATION')) {
            return 60; // 1 minute
        }

		$audio_file = storage_path() . '/app/audios/' . $name;
        $result_json = shell_exec('ffprobe -v quiet -print_format json -show_format -show_streams "'. $audio_file .'"');
		$result = json_decode($result_json, true);

		if (!isset($result['streams'][0]['duration'])) {
			return false;
		}

		$duration = $result['streams'][0]['duration'];

		if (!is_numeric($duration) || $duration == 0) {
			return false;
		}

		return $duration; // in seconds
	}

	public static function checkType() {

	}


	public static function randomName() {
		$file = storage_path() . '/audios/' . rand(1, 100000000);
		while(file_exists($file)) {
			$file = storage_path() . '/audios/' . rand(1, 100000000);
		}

		return $file;
	}

	public function changeStatusIfAllAudioSlicesFinished() {
		$statuses = $this->slices()->lists('status');
		foreach ($statuses as $status) {
			if ($status !== 'finished') {
				return false;
			}
		}

		// check audio status
		if ($this->status === 'in_transcription') {
			$this->status = 'available_for_editing';
			$this->save();

			Email::editorNewAudio($this);

			return true;
		}


	}

	public function isInEditing()
	{
		return (bool)Audio::where('id', $this->id)->inEditing()->count();
	}

	public function isInSubtitling()
	{
		return (bool)Audio::where('id', $this->id)->inSubtitling()->count();
	}

	public function link($params = null)
	{
		if (empty($params)) {
			return $this->download();
		}
		if ($params === 'take') {
			return '/editing_jobs/' . $this->id . '/take';
		}
		if ($params === 'cancel') {
			return '/editing_jobs/' . $this->id . '/cancel';
		}
		if ($params === 'finish') {
			return '/editing_jobs/' . $this->id . '/finish';
		}
		if ($params === 'takeForSubtitling') {
			return "/subtitling_jobs/{$this->id}/take";
		}
		if ($params === 'cancelSubtitling') {
			return "/subtitling_jobs/{$this->id}/cancel";
		}
		if ($params === 'finishSubtitling') {
			return "/subtitling_jobs/{$this->id}/finish";
		}

		abort(404, 'Wrong parameter');
	}

	public function download()
	{
		if ($this->url) {
			// external url (youtube, vimeo)
			return $this->url;
		} elseif (!$this->isFileDeleted()) {
			// file
			return '/download/' . $this->id;
		} else {
			Log::info('File not found (or was deleted) 2', ['audio_id' => $this->id]);
			abort(404, 'File not found (or was deleted) 2');
		}
	}

	/**
	 * how much editor will get for this audio
	 *
	 * @param User $user If audio without user, you can set it to see price for him
	 * @return float|int
	 */
	public function editorPrice(User $editor = null)
	{
        // price override by admin
        if ($this->editor_price_override) {
            return round($this->editor_price_override, 2);
        }

		// if file information is not entered jet
		if ($this->duration == 0) {
			return 0;
		}

		$duration = $this->duration;

		if (!$editor) {
			$editor = $this->editor;
		}
		$editor_price_per_minute = UserPricePerMinute::editor($this->order->tat, $this->order->timestamping, $editor);

		$total = round($editor_price_per_minute * ($duration / 60), 2);

		return $total;
	}

	// how much client needs to pay for this audio file
	public function clientPrice()
	{
		die('it is best not to use this'); // there is complex function Order->clientPrice()

		$duration_in_minutes = $this->duration / 60;

		$tat_price = $this->order->tat->client_price_per_minute;
		$text_format_price = $this->order->textFormat->client_price_per_minute;
		$timestamping_price = $this->order->timestamping->client_price_per_minute;
		$speaker_price = $this->order->speaker->client_price_per_minute;


		$price_per_minute = $tat_price + $text_format_price + $timestamping_price + $speaker_price;
		$price = $price_per_minute * $duration_in_minutes;

		return round($price, 2);
	}

	public function subtitlerPrice(User $subtitler = null)
	{
		$duration = $this->duration;
//		$tat_price_per_minute = $this->order->tat->editor_price_per_minute;
//		$timestamping_price_per_minute = $this->order->timestamping->editor_price_per_minute;

		if (!$subtitler) {
			$subtitler = $this->subtitler;
		}
		$subtitler_price_per_minute = UserPricePerMinute::subtitler($this->order->subtitle, $subtitler);

		$total = round($subtitler_price_per_minute * ($duration / 60), 2);

		return $total;
	}

	// takes file from vendor folder ant puts into audios folder
	public static function takeFile($vendor_filename)
	{
		if (stripos($vendor_filename, '/') !== false || stripos($vendor_filename, '/') !== false ) {
			Log::info('somebody is trying to inject directory instead of filename');
			exit;
		}

		$old_path = public_path() . '/vendor/upload/server/php/files/' . $vendor_filename;

		if (!file_exists($old_path)) {
			Log::info('file doesnt exists');
			return false;
		}

		$audio = new Audio();
		$audio->order_id = Order::getUnpaidOrder()->id;
		$audio->original_filename = $vendor_filename;
		$audio->save();

		$new_path = storage_path() . '/app/audios/' . $audio->id;

		$result = rename($old_path, $new_path);
		if (!$result) {
			File::delete($old_path);
			$audio->delete();
			Log::info('cant rename uploaded file');
			return false;
		}

		$original_duration = Audio::getDuration($audio->id);
		if (!$original_duration) {
			File::delete($new_path);
			$audio->delete();
			Log::info('can\'t get uploaded file duration');
			return false;
		}

		$audio->original_duration = $original_duration;
		$audio->size = ceil(filesize(storage_path() . '/app/audios/' . $audio->id) / 1048576);

		$audio = Audio::checkAndSaveAudio($audio);

		// clean tmp upload folder (1 hour old files)
		$files = \Illuminate\Support\Facades\File::files(public_path() . '/vendor/upload/server/php/files');
		foreach ($files as $file) {
			$last_modified = \Illuminate\Support\Facades\File::lastModified($file);
			if (time() - $last_modified > 3600) {
				\Illuminate\Support\Facades\File::delete($file);
			}
		}

		return $audio;
	}

    public static function checkAndSaveAudio(Audio $audio) {

		if (!$audio->original_duration) {
			Log::error('no audio duration set', $audio->toArray());
			print_r('ERROR: no audio duration set'); die(' ' . __FILE__ . ':' . __LINE__);
		}

		if (!$audio->order_id) {
			$audio->order_id = Order::getUnpaidOrder()->id;
		}

		if (!$audio->till) {
			$audio->till = $audio->original_duration;
		}

		$audio->save();

		return Audio::findOrFail($audio->id); // doesnt return comment if not retrieved again


//		$params['order_id'] =
//
//		$params['original_duration'] = self::getDuration($params['original_filename']);
//		$params['size'] = ceil(filesize(storage_path() . '/app/audios/' . $params['original_filename']) / 1048576);




	}

	public function isLate()
	{
		return Audio::where('id', $this->id)->late()->exists();
	}

	public function makeAvailableAgain()
	{
		AudioLog::log($this->editor->id, $this->id, 'deadline_miss');

		$this->editor_deadline_at = null;
		$this->editor_id = null;
		$this->editor_price = null;
		$this->status = 'available_for_editing';
		$this->save();
	}

}
