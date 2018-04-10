<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AudioSlice extends Model {

	public static function boot()
	{
		parent::boot();


		AudioSlice::deleting(function ($audio_slice) {
			deleteAll($audio_slice->logs);
			deleteOne($audio_slice->transcription);
		});


	}


	// -------------------------------- relationships ------------------------------------------------------------------
	public function audio() {
		return $this->belongsTo('App\Audio');
	}

	public function transcription()
	{
		return $this->hasOne('App\AudioSliceTranscription');
	}

	public function transcriber()
	{
		return $this->belongsTo('App\User', 'transcriber_id');
	}

	public function logs()
	{
		return $this->hasMany('App\AudioSliceLog', 'slice_id');
	}

	// -------------------------------- attributes ---------------------------------------------------------------------
	public function getTimeLeftAttribute()
	{
		return timeLeftFull(leftSeconds($this->deadline_at));
	}

	public function getForHumansFromAttribute()
	{
		return secondsToTime($this->from);
	}

	public function getForHumansTillAttribute()
	{
		return secondsToTime($this->till);
	}

	public function getDurationAttribute()
	{
		return $this->till - $this->from;
	}

	public function setTranscriberPriceOverrideAttribute($value)
	{
		if (!$value) {
			$this->attributes['transcriber_price_override'] = null;
		} else {
			$this->attributes['transcriber_price_override'] = $value;
		}
	}

	public function setRatingAttribute($value)
	{
		if (!$value) {
			$this->attributes['rating'] = null;
		} else {
			$this->attributes['rating'] = $value;
		}
	}

	// ------------------------------ scopes ---------------------------------------------------------------------------

	// can be taken for transcription
	public function scopeAvailable($query) {
		$query->where('audio_slices.status', 'available');
		//$query->whereNull('transcriber_id');
	}

	public function scopeAvailableForUser($query, $user) {
		$user_language_ids = $user->languages->lists('id');
		$query->available()->whereHas('audio', function($q) use ($user_language_ids) {
			$q->whereHas('order', function($q) use ($user_language_ids) {
				$q->whereHas('language', function($q) use ($user_language_ids) {
					$q->whereIn('id', $user_language_ids);
				});
			});
		});
	}

	// transcriber is transcribing
	public function scopeInProgress($query) {

		$query->where('audio_slices.status', 'in_progress');
//		$query
//			->whereNotNull('transcriber_id') 	// someone taken the job
//			->has('AudioSliceTranscription', '=', 0) 	// and didn't attached transcriptions
		;
	}

	public function scopeFinished($query) {
		$query->where('audio_slices.status', 'finished');

//		$query->has('AudioSliceTranscription'); // transcription is attached
	}

	public function scopeLateFirst($query)
	{
		$query->join('audios as audios', 'audio_slices.audio_id', '=', 'audios.id')
			->join('orders as orders', 'audios.order_id', '=', 'orders.id')
			->orderBy('orders.deadline_at', 'asc')
			->select('audio_slices.*');    // just to avoid fetching anything from joined table
	}

	public function scopeLate($q)
	{
		$q->whereNotNull('deadline_at')->where('status', '!=', 'finished')->where('deadline_at', '<', date('Y-m-d H:i:s'));
	}

	public function scopeFinishedOnTime($query)
	{
 		$query->finished()->whereRaw('finished_at <= deadline_at');
	}


	public function scopeMissedDeadline($query) // transcriber missed deadline
	{
		$query->inProgress()->where('deadline_at', '<', date('Y-m-d H:i:s'));
	}

	public function scopeFilter($q, Request $request)
	{
		if ($request->has('audio_id')) {
			$q->where('audio_id', $request->audio_id);
		}
		if ($request->has('transcriber_id')) {
			$q->where('transcriber_id', $request->transcriber_id);
		}

	}


//	public function scopeAvailableToUser($query, $user) {
//		$query
//			->whereNull('transcriber_id'); // nobody is taken the job
//	}

//	public function scopeInProgressByUser($query, $user) {
//		$query
//			->where('transcriber_id', $user->id)
//			->has('AudioPartTranscription', '=', 0)
//		;
//	}
//
//	public function scopeFinishedByUser($query, $user) {
//		$query
//			->where('transcriber_id', $user->id)
//			->has('AudioPartTranscription');
//	}


	// -------------------------------------- methods ------------------------------------------------------------------

	/**
	 * Is job available
	 *
	 * @param $user
	 * @param $check_time_delay
	 * @return bool
	 */
	public function isAvailable($user, $check_time_delay = true)
	{
		if ($this->status !== 'available') {
			return false;
		}

		// language
		if (!in_array($this->audio->order->language_id, $user->languages->lists('id'))) {
			return false;
		}

		// time delay
		if ($check_time_delay) {
			if (!$this->doesTimePassed($user)) {
				return false;
			}
		}

		// job count
		if ($user->inProgressJobCount() >= $user->job_limit) {
			return false;
		}

		return true;
	}

	public function isRated() // by editor
	{
		if ($this->rating || $this->editor_comment) {
			return true;
		} else {
			return false;
		}
	}

	public function isInProgress()
	{
		return (bool)AudioSlice::where('id', $this->id)->inProgress()->count();
	}

	public function assignToUser($user) // ??????????????
	{
		$user->takeAudioSlice($this->id);
	}

	// if user missed deadline
	public function makeAvailableAgain()
	{
		AudioSliceLog::log($this->transcriber->id, $this->id, 'deadline_miss');
		
		$this->deadline_at = null;
		$this->transcriber_id = null;
		$this->status = 'available';
		$this->save();
	}

	public static function newAudioSlice(array $params)
	{
		$slice = new AudioSlice();
		$slice->audio_id = $params['audio_id'];
		$slice->from = $params['from'];
		$slice->till = $params['till'];
		$slice->save();

		return $slice;
	}

	public function link($params)
	{
		if ($params === 'take') {
			return '/transcription_jobs/' . $this->id . '/take';
		}
		if ($params === 'cancel') {
			return '/transcription_jobs/' . $this->id . '/cancel';
		}
		if ($params === 'finish') {
			return '/transcription_jobs/' . $this->id . '/finish';
		}
		if ($params === 'rate') {
			return route('rate_audio_slice', ['audio_slice_id' => $this->id]);
		}

		abort(404, 'Wrong parameter');
	}

	/**
	 * How much time left till slice will be available for certain user
	 *
	 * @return integer
	 */
	public function minutesTillTimePassed($user)
	{
		// rating for certain language
		$slice_language_id = $this->audio->order->language_id;
		$rating = $user->rating($slice_language_id);

		$user_job_delay = round(RatingDelay::getDelay($rating) / 60);

		$time_when_available = $this->created_at->addMinute($user_job_delay);
		$minutes_till_available = \Carbon\Carbon::now()->diffInMinutes($time_when_available, null, false);

		if ($minutes_till_available < 0) {
			return 0;
		}

		return $minutes_till_available;
	}

	public static function avgRating($user_id, $language_id)
	{
//		$avg_rating = AudioSlice::whereHas('audio', function($query) use ($language_id) {
//			$query->where('language_id', $language_id);
//		})->where('transcriber_id', $user_id)->whereNotNull('rating')->avg('rating');

		$avg_rating = AudioSlice::whereHas('audio', function($query) use ($language_id) {
			$query->whereHas('order', function($query) use ($language_id) {
				$query->where('language_id', $language_id);
			});
		})->where('transcriber_id', $user_id)->whereNotNull('rating')->avg('rating');

		return $avg_rating;
	}



	/**
	 * Checks if certain number of minutes passed after job was created
	 *
	 * @param $user
	 * @return bool
	 */
	private function doesTimePassed($user)
	{
		if ($this->minutesTillTimePassed($user) > 0) {
			return false;
		} else {
			return true;
		}
	}

//	public function money($user_role)
//	{
//		if ($user_role === 'transcriber') {
//			$duration_in_minutes = $this->duration / 60;
//
//			$price_per_minute =
//				$this->audio->tat->transcriber_price_per_minute // turnaround time
//				+ $this->audio->timestamping->transcriber_price_per_minute // timestamping
//			;
//
//			$price = $duration_in_minutes * $price_per_minute;
//
//			return round($price, 2);
//		}
//
//		return '?????';
//	}

	/**
	 * Slice transcriber price
	 *
	 * @param User $transcriber You can set for which transcriber calculate the price. If not set, code will use audio slice transcriber
	 * @return float
	 */
	public function transcriberPrice(User $transcriber = null) {
		// price override by admin
		if ($this->transcriber_price_override) {
			return round($this->transcriber_price_override, 2);
		}

		$duration_in_minutes = $this->duration / 60;

		if (!$transcriber) {
			$transcriber = $this->transcriber;
		}
		$transcriber_price_per_minute = UserPricePerMinute::transcriber($this->audio->order->tat, $transcriber);

		$price = $duration_in_minutes * $transcriber_price_per_minute;

		return round($price, 2);
	}




}
