<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Coupon extends Model {

	protected $guarded = [

	];

	// with validation
	public static function getCoupon($code)
	{
		$coupon = Coupon::where('code', trim($code))->first();

		if (!$coupon || !CouponUsage::canBeUsed($coupon, Auth::user())) {
			return null;
		} else {
			return $coupon;
		}
	}



}
