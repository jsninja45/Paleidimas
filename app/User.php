<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use App\AudioSlice;
use App\UserRating;
use Illuminate\Http\Request;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'paypal_email',
	];


	public static $rules = [
		'email' => 'required|email|unique:users',
		'password' => 'required',
	];

//	public function getDates()
//	{
//		return [
//			'updated_at',
//			'created_at',
//			'deleted_at',
//			'deadline_at',
//		];
//	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public static function boot()
	{
		parent::boot();

		static::created(function($user) {
			$user_info = new UserPricePerMinute();
			$user_info->user_id = $user->id;
			$user_info->save();

			if (!$user->hasRole('client')) {
				$user->addRole('client');
			}
		});
	}

	// ------------------------------------------ relationships --------------------------------------------------------

	public function roles() {
		return $this->belongsToMany('App\Role');
	}

	public function languages() {
		return $this->belongsToMany('App\Language');
	}

	public function editorAudios()
	{
		return Audio::where('editor_id', $this->id);
	}

	public function subtitlerAudios()
	{
		return Audio::where('subtitler_id', $this->id);
	}

	public function userAudios()
	{
		$user_id = $this->id;

		return Audio::whereHas('order', function($query) use ($user_id) {
			$query->where('client_id', $user_id);
		});
	}

	public function audioSlices()
	{
		return $this->hasMany('App\AudioSlice', 'transcriber_id');
//		return AudioSlice::where('transcriber_id', $this->id);
	}

	public function order() // ??????????????????????????????
	{
		return $this->belongsTo('Order');
	}

	public function transactions()
	{
		return $this->hasMany('App\Transaction');
	}

	public function orders() {
		return $this->hasMany('App\Order', 'client_id');
	}

	public function ratings()
	{
		return $this->hasMany('App\UserRating');
	}

	public function audioSliceTranscriptions()
	{
		return $this->hasMany('App\AudioSliceTranscription');
	}

	public function notifications()
	{
		return $this->hasMany('App\Notification');
	}

	public function ordersPaid()
	{
		return $this->orders()->paid();
	}

	public function transcriberAudioSlices()
	{
		return $this->hasMany('App\AudioSlice', 'transcriber_id');
	}

	public function messages()
	{
		return $this->hasMany('App\Message', 'recipient_id');
	}

	public function customRates()
	{
		return $this->hasOne('App\UserPricePerMinute');
	}

	public function bonuses()
	{
		return $this->hasMany('App\Bonus');
	}

	public function oldOrders()
	{
		return $this->hasMany('App\OldOrder', 'uaid', 'old_id');
	}

	// -------------------------------------- scopes ------------------------------------------------------------------

	public function scopeClient($q)
	{
		$q->whereHas('roles', function($q) {
			$q->where('name', 'client');
		});
	}

	// not transcriber or something else
	public function scopeJustClient($q)
	{
		$q->client()->has('roles', '=', 1); // just one role and it is client
	}

	public function scopeTranscriber($q)
	{
		$q->whereHas('roles', function($q) {
			$q->where('name', 'transcriber');
		});
	}

	public function scopeEditor($q)
	{
		$q->whereHas('roles', function($q) {
			$q->where('name', 'editor');
		});
	}

	public function scopeSubtitlers($q)
	{
		$q->whereHas('roles', function($q) {
			$q->where('name', 'subtitler');
		});
	}

	public function scopeWorkers($q)
	{
		$q->whereHas('roles', function($q) {
			$q->whereIn('name', ['transcriber', 'editor', 'subtitler']);
		});
	}

	public function scopeAdmin($q)
	{
		$q->whereHas('roles', function($q) {
			$q->where('name', 'admin');
		});
	}

	public function scopeUrlFilter($q, Request $request)
	{
		if ($request->order === 'spent') {
			$q->selectRaw('*, (SELECT SUM(amount) as total_spent FROM transactions WHERE type = "order" AND transactions.user_id = users.id) as  spent')
				->orderBy('spent', 'desc');
		} else if ($request->order === 'rating') {
            $q->selectRaw('*, (SELECT AVG(rating) FROM audio_slices WHERE audio_slices.transcriber_id = users.id) AS rating')
                ->orderBy('rating', 'desc');
        }

		if ($request->email) {
			$q->where('email', $request->email);
		}

		if ($request->has('language')) {
			$q->whereHas('languages', function ($q) use ($request) {
				$q->where('languages.id', $request->language);
			});
		}
	}

	public function scopeNotDeleted($q)
	{
		$q->where('deleted', 0);
	}

	public function scopeCanReceiveNewsletter($q)
	{
		$q->notDeleted()->where('newsletter', 1);
	}

	// want to receive emails about new jobs
	public function scopeReceiveNewJobEmails($q)
	{
		$q->where('new_job_email', 1);
	}

//	public function scopeCreated24HoursAgo($query)
//	{
//		$query->where('users.created_at', '>=', date('Y-m-d H:i:s', strtotime('-25 hour')))->where('users.created_at', '<', date('Y-m-d H:i:s', strtotime('-24 hour')));
//	}
//
//	public function scopeCreated72HoursAgo($query)
//	{
//		$query->where('users.created_at', '>=', date('Y-m-d H:i:s', strtotime('-73 hour')))->where('users.created_at', '<', date('Y-m-d H:i:s', strtotime('-72 hour')));
//	}

	// -------------------------------------- attributes ---------------------------------------------------------------

	public function setPasswordAttribute($pass){
		$this->attributes['password'] = Hash::make($pass);
	}

	// ------------------------------------- methods -------------------------------------------------------------------

	/**
	 * Check if user has at least one of the supplied roles
	 *
	 * @param $roles string|array|or_multiple_parameters
	 * @return bool
	 */
	public function hasRole($roles) {

		if (func_num_args() > 1) {
			$roles = func_get_args();
		} else {
			$roles = (array)$roles;
		}

		$user_roles = $this->roles()->lists('name');
		foreach ($user_roles as $user_role) {
			if (in_array($user_role, $roles)) {
				return true;
			}
		}

		return false;
	}

	public function addRole($role_name)
	{
		$role = Role::where('name', $role_name)->firstOrFail();

		RoleUser::addRole($this, $role);
	}

	public function takeAudioSlice($slice_id) // to user
	{
		$audio_slice = AudioSlice::findOrFail($slice_id);

        if (!$audio_slice->isAvailable($this)) {
			abort(403, 'User can\'t take this audio slice.');
		}

		$audio_slice->transcriber_id = $this->id;
		$audio_slice->transcriber_price = $audio_slice->transcriberPrice();
		$audio_slice->status = 'in_progress';
		$audio_slice->deadline_at = date('Y-m-d H:i:s', time() + $audio_slice->audio->order->tat->max_transcription_duration);
		$audio_slice->save();

		AudioSliceLog::log($this->id, $slice_id, 'take');
	}

	public function finishAudioSliceJob($slice_id)
	{
		$audio_slice = AudioSlice::findOrFail($slice_id);

		if (!$this->canUserFinishAudioSlice($audio_slice)) {
			abort(403, 'User can\'t finish this audio slice.');
		}

		$audio_slice->status = 'finished';
		$audio_slice->finished_at = date('Y-m-d H:i:s');
		$audio_slice->save();

		// change audio status
		$audio_slice->audio->changeStatusIfAllAudioSlicesFinished();

		AudioSliceLog::log($this->id, $slice_id, 'finish');
	}

	public function cancelAudioSliceJob($slice_id)
	{
		$slice = AudioSlice::findOrFail($slice_id);

		if ($slice->transcriber_id != $this->id) {
			abort('401', 'This audio slice doesn\'t belong to user');
		}
		if (!$slice->isInProgress()) {
			abort('401', 'This audio slice is not in progress');
		}

		$slice->transcriber_id = null;
		$slice->transcriber_price = null;
		$slice->finished_at = null;
		$slice->deadline_at = null;
		$slice->status = 'available';
		$slice->save();

		AudioSliceLog::log($this->id, $slice_id, 'cancel');
	}

	public function takeAudioJob($audio_id) // for editing
	{
		$user = Auth::user();

		$audio = Audio::findOrFail($audio_id);

		if (!$audio->canUserTakeJob($this)) {
			abort(403, 'User can\'t take this audio.');
		}

		$audio->editor_id = $this->id;
		$audio->editor_price = $audio->editorPrice($user);
		$audio->status = 'in_editing';
        $audio->editor_deadline_at = editorDeadlineAt($audio);
		$audio->save();

		AudioLog::log($this->id, $audio_id, 'take');
	}

	public function finishAudioJob($audio_id)
	{
		$audio = Audio::findOrFail($audio_id);

		if (!$this->canUserFinishAudio($audio)) {
			abort(403, 'User can\'t finish this audio.');
		}

		if ($audio->order->doesOrderNeedSubtitles()) {
			// needs subtitles
			$audio->status = 'available_for_subtitling';
			$audio->save();

			Email::subtitlerNewJob($audio);
		} else {
			// doesnt need subtitles
			$audio->status = 'finished';
			$audio->finished_at = date('Y-m-d H:i:s');
			$audio->save();

			$audio->order->changeStatusIfAllAudiosFinished();

			AudioLog::log($this->id, $audio_id, 'finish');

			Email::clientAudioFinish($audio);
		}
	}

	public function cancelAudioJob($audio_id)
	{
		$audio = Audio::findOrFail($audio_id);

		if ($audio->editor_id != $this->id) {
			abort('401', 'This audio doesn\'t belong to the user');
		}
		if (!$audio->isInEditing()) {
			abort('401', 'This audio slice is not in progress');
		}

		$audio->editor_id = null;
		$audio->editor_price = null;
		$audio->status = 'available_for_editing';
		$audio->save();

		AudioLog::log($this->id, $audio_id, 'cancel');
	}

	public function takeSubtitlingJob($audio_id) // for editing
	{
		$user = Auth::user();

		$audio = Audio::findOrFail($audio_id);

		if (!$audio->canUserTakeJobForSubtitling($this)) {
			abort(403, 'User can\'t take this audio.');
		}

		$audio->subtitler_id = $this->id;
		$audio->subtitler_price = $audio->subtitlerPrice($user);
		$audio->status = 'in_subtitling';
		$audio->save();

//		AudioLog::log($this->id, $audio_id, 'take');
	}

	public function finishSubtitlingJob($audio_id)
	{
		$audio = Audio::findOrFail($audio_id);

		if (!$this->canUserFinishSubtitling($audio)) {
			abort(403, 'User can\'t finish this audio.');
		}

		$audio->status = 'finished';
		$audio->finished_at = date('Y-m-d H:i:s');
		$audio->save();

		$audio->order->changeStatusIfAllAudiosFinished();

//		AudioLog::log($this->id, $audio_id, 'finish');

		Email::clientAudioFinish($audio);
	}

	public function cancelSubtitlingJob($audio_id)
	{
		$audio = Audio::findOrFail($audio_id);

		if ($audio->subtitler_id != $this->id) {
			abort('401', 'This audio doesn\'t belong to the user');
		}
		if (!$audio->isInSubtitling()) {
			abort('401', 'This audio is not in subtitling');
		}

		$audio->subtitler_id = null;
		$audio->subtitler_price = null;
		$audio->status = 'available_for_subtitling';
		$audio->save();

		AudioLog::log($this->id, $audio_id, 'cancel');
	}

	public function inProgressJobCount()
	{
		// audios + audio slices
		return
			$this->audioSlices()->inProgress()->count()
			+ $this->editorAudios()->inEditing()->count()
			+ $this->subtitlerAudios()->inSubtitling()->count()
			;
	}

	public function canUserFinishAudio($audio) // finish editing
	{
		if ($audio->editor_id != $this->id) {
			Log::error('audio belongs to other user');
			return false;
		}

		if ($audio->status !== 'in_editing') {
			Log::error('audio is not in editing');
			return false;
		}

		if (!$audio->transcriptions()->exists()) {
//			Log::error('audio doesnt have transcription');
			return false;
		}

		return true;
	}

	public function canUserFinishSubtitling($audio)
	{
		if ($audio->subtitler_id != $this->id) {
			Log::error('audio belongs to other user');
			return false;
		}

		if ($audio->status !== 'in_subtitling') {
			Log::error('audio is not in subtitling');
			return false;
		}

		if (!$audio->subtitles()->exists()) {
//			Log::error('audio doesnt have transcription');
			return false;
		}

		return true;
	}

	public function canUserFinishAudioSlice($audio_slice)
	{
		if ($audio_slice->transcriber_id != $this->id) {
			return false;
		}

		if ($audio_slice->status !== 'in_progress') {
			return false;
		}

		if (!$audio_slice->transcription) {
			return false;
		}

		return true;
	}

	/**
	 * @deprecated there is there is special table for calculated user ratings
	 *
	 * @return float|string
	 */
	public function transcriptionRating()
	{
		$rating = $this->audioSlices()->whereNotNull('rating')->avg('rating');

		return $rating;
	}

	public function editingRating()
	{
		$rating = $this->editorAudios()->whereNotNull('rating')->avg('rating');

		return $rating;
	}

	public function spent()
	{
		return $this->transactions()->where('type', 'order')->sum('amount');
	}

	// how much earned money, that is still not paid
	public function notPaidEarnings()
	{
		$last_transaction = $this->transactions()->whereNotNull('worker_payment_till')->orderBy('created_at', 'desc')->first();
		if ($last_transaction) { // paid this user in the past
			$paid_until = $last_transaction->worker_payment_till;

			$transcriber_price = $this->audioSlices()->where('created_at', '>=', $paid_until)->sum('transcriber_price');
			$editor_price = $this->editorAudios()->where('created_at', '>=', $paid_until)->sum('editor_price');

			return $transcriber_price + $editor_price;
		} else { // no payments was made yet to this user
			$transcriber_price = $this->audioSlices()->sum('transcriber_price');
			$editor_price = $this->editorAudios()->sum('editor_price');

			return $transcriber_price + $editor_price;
		}
	}

	public function rating($language_id)
	{
		$user_rating = UserRating::where('user_id', $this->id)->where('language_id', $language_id)->first();

		if (!$user_rating || $user_rating->rating == 0) {
			return null;
		}

		return $user_rating->rating;
	}

	// how much audios user has uploaded
	public function paidMinutes()
	{
        if ($this->full_discount) {
            return 720;
        }

		$duration = 0;

		$paid_orders = $this->orders()->onlyPaid()->get();
		foreach ($paid_orders as $order) {
			$duration += $order->totalAudioDuration();
		}

		return $duration / 60;
	}

	public function isEditingLimitReached()
	{
		$currently_editing_jobs = $this->editorAudios()->inEditing()->count();

		if ($this->job_limit >= $currently_editing_jobs) {
			return true;
		} else {
			return false;
		}
	}

	public function onlySocialLogin() {
		if ($this->password === '') {
			return true;
		} else {
			return false;
		}
	}

	public function link($action)
	{
		if ($action === 'undelete') {
			return route('admin.users.undelete', [$this->id]);
		}

		die('wrong action');
	}

}
