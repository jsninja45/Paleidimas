<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Chat;
use App\Observers\MessageObserver;
use Illuminate\Support\Facades\Auth;



class Message extends Model {

	public static function boot()
	{
		parent::boot();

		Message::observe(new MessageObserver);
	}

	// ------------------------------- relationships -------------------------------------------------------------------
	public function sender() {
		return $this->belongsTo('App\User', 'sender_id');
	}

	public function recipient() {
		return $this->belongsTo('App\User', 'recipient_id');
	}


	// on per user
	public function scopeChatWith($query, $user_id)
	{
		$query->where(function($query) use ($user_id) {
			$query->where('recipient_id', $user_id)->where('sender_id', Auth::id());
		})->orWhere(function($query) use ($user_id) {
			$query->where('sender_id', $user_id)->where('recipient_id', Auth::id());
		});
	}

	public function scopeLastMessageWithUser($query, $sender_id, $recipient_id) {

		list($user1_id, $user2_id) = Chat::correctIdOrder($sender_id, $recipient_id); // important

		$message_ids = Chat::where('user1_id', $user1_id)->orWhere('user2_id', $user2_id)->lists('last_message_id'); // just one id
		if (!empty($message_ids)) {
			$query->whereIn('id', $message_ids);
		}
	}

	public function scopeNotSeen($q)
	{
		$q->where('seen', 0);
	}

	public function scopeUrlFilter($q, $request)
	{
        if ($request->has('sender') && $request->has('recipient')) { // see messages in sent both ways
			$q->where(function($q) use ($request) {
				$q->where('sender_id', $request->sender)->orWhere('sender_id', $request->recipient);
			})->where(function($q) use ($request) {
				$q->where('recipient_id', $request->sender)->orWhere('recipient_id', $request->recipient);
			});
        } elseif ($request->has('sender')) {
            $q->where('sender_id', $request->sender);
        } elseif ($request->has('recipient')) {
            $q->where('recipient_id', $request->recipient);
        }
	}


	// -------------------------------------------- methods ------------------------------------------------------------
	public static function setSeen($sender_id)
	{
		Message::where('sender_id', $sender_id)->update(['seen' => 1]);
	}

	public function link()
	{
		return route('messages_with', [$this->sender->id]);
	}

}
