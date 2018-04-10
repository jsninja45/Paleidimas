<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RatingDelay extends Model {

	protected $fillable = [
		'rating_till',
	];

	public static function getDelay($rating)
	{
		if (!$rating || $rating == 0) {
			return Config::get('slice_no_rating_delay') * 60;
		}

		$rating_delay = RatingDelay::where('rating_till', '<=', $rating)->orderBy('rating_till', 'desc')->first();

		if (!$rating_delay) {
			$rating_delay = RatingDelay::orderBy('rating_till', 'asc')->firstOrFail(); // smallest
		}
		
		return $rating_delay->delay; // seconds
	}

}
