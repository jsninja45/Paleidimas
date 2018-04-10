<?php namespace App;

// This class is to help make code simpler for finding chat messages

use Illuminate\Database\Eloquent\Model;

class Chat extends Model {

	public function message()
	{
		return $this->hasOne('App\Message');
	}


	/**
	 * Doesn't matter which is sender and which user is recipient
	 *
	 * @param $user1_id
	 * @param $user2_id
	 * @param $message_id
	 */
	public static function newMessage($user1_id, $user2_id, $message_id)
	{
		list($user1_id, $user2_id) = self::correctIdOrder($user1_id, $user2_id); // important

		$chat = Chat::where('user1_id', $user1_id)->where('user2_id', $user2_id)->first();
		if (!$chat) {
			$chat = new Chat();
			$chat->user1_id = $user1_id;
			$chat->user2_id = $user2_id;
			$chat->save();
		}

		$chat->last_message_id = $message_id;
		$chat->save();

		return true;
	}

	// list($user1_id, $user2_id) = correctIdOrder($user1_id, $user2_id); // important
	public static function correctIdOrder($user1_id, $user2_id)
	{
		// user2_id is always bigger than user1_id
		if ($user1_id > $user2_id) {
			$tmp = $user1_id;
			$user1_id = $user2_id;
			$user2_id = $tmp;
		}

		return [$user1_id, $user2_id];
	}

}
