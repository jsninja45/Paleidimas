<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model {

	public static function canBeUsed($coupon, $user)
	{
		if ($coupon->single_use) {
			if (CouponUsage::where('user_id', $user->id)->where('coupon_id', $coupon->id)->exists()) {
				return false;
			}
		}

		if ($coupon->expires_at) {
			$now = date('Y-m-d H:i:s');
			if ($coupon->expires_at < $now) {
				return false;
			}
		}

		return true;
	}

	public static function useCoupon($coupon, $user)
	{
		if ($coupon->single_use) {
			$coupon_usage = new CouponUsage();
			$coupon_usage->user_id = $user->id;
			$coupon_usage->coupon_id = $coupon->id;
			$coupon_usage->save();
		}
	}

}
