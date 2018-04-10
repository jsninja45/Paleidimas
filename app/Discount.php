<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model {

	protected $visible = [
		'minutes',
		'percent',
	];

	/**
	 * @param $minutes
	 * @return Discount
	 */
	public static function discount($minutes)
	{
		$discount = Discount::where('minutes', '<=', $minutes)->orderBy('minutes', 'desc')->first();

		if (!$discount) {
			$discount = new Discount();
			$discount->minutes = 0;
			$discount->percent = 0;
		}

		return $discount;
	}

	/**
	 * Return null if max discount reached (no next discount)
	 *
	 * @param $minutes
	 * @return Discount|null
	 */
	public static function next_discount($minutes)
	{
		$discount = Discount::where('minutes', '>', $minutes)->orderBy('minutes', 'asc')->first();

		return $discount;
	}

}
