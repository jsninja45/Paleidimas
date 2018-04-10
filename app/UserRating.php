<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRating extends Model {

	public function language()
	{
		return $this->belongsTo('App\Language');
	}


	public static function recalculateAll($user_id)
	{
		UserRating::where('user_id', $user_id)->delete();

		$language_ids = User::findOrFail($user_id)->languages->lists('id');

		foreach ($language_ids as $language_id) {
			$rating = AudioSlice::avgRating($user_id, $language_id);
			$user_rating = new UserRating();
			$user_rating->user_id = $user_id;
			$user_rating->language_id = $language_id;
			$user_rating->rating = (!empty($rating)) ? $rating : '';
			$user_rating->save();
		}
	}

}
