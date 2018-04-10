<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPricePerMinute extends Model {

	// so far used only for calculations in admin panel

	protected $fillable = [
		'transcriber_price_1_tat',
		'transcriber_price_3_tat',
		'transcriber_price_7_tat',
		'transcriber_price_14_tat',
		'editor_price_1_tat_no_timestamping',
		'editor_price_3_tat_no_timestamping',
		'editor_price_7_tat_no_timestamping',
		'editor_price_14_tat_no_timestamping',
		'editor_price_1_tat_with_timestamping',
		'editor_price_3_tat_with_timestamping',
		'editor_price_7_tat_with_timestamping',
		'editor_price_14_tat_with_timestamping',
	];

	public function getFillable() {
		return $this->fillable;
	}

	public static function transcriber($tat, User $transcriber = null)
	{
		$price = null;
		if ($transcriber) { // have user set manually price?
			$prices = UserPricePerMinute::where('user_id', $transcriber->id)->firstOrFail();

			if ($prices->enabled) {
				$param = 'transcriber_price_' . $tat->days . '_tat';
				$price = $prices->attributes[$param];
			}
		}

		if (!$price) {
			// default price
			$price = $tat->transcriber_price_per_minute;
		}

		return $price;
	}

	public static function editor(Tat $tat, Timestamping $timestamping, User $editor = null)
	{
		$price = null;
		if ($editor) { // have user set manually price?
			$prices = UserPricePerMinute::where('user_id', $editor->id)->firstOrFail();

			if ($prices->enabled) {
				$timestamping_text = $timestamping->id == 1 ? 'no_timestamping' : 'with_timestamping';
				$param = 'editor_price_' . $tat->days . '_tat_' . $timestamping_text;
				$price = $prices->attributes[$param];
			}
		}

		if (!$price) {
			// default price
			$tat_price = $tat->editor_price_per_minute;
			$timestamping_price = $timestamping->editor_price_per_minute;
			$price = $tat_price + $timestamping_price;
		}

		return $price;
	}

	public static function subtitler(Subtitle $subtitle, User $subtitler = null)
	{
		$price = null;
		if ($subtitler) { // have user set manually price?
			$prices = UserPricePerMinute::where('user_id', $subtitler->id)->firstOrFail();

			if ($prices->enabled) {
				$price = $prices->attributes['subtitler_price'];
			}
		}

		if (!$price) {
			// default price
			$subtitling_price = $subtitle->subtitler_price_per_minute;
			$price = $subtitling_price;
		}

		return $price;
	}

}
