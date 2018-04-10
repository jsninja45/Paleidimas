<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Coupon;
use App\Order;

class CouponController extends Controller {
	use CRUDTrait;

	protected static $model = 'App\Coupon';
	protected static $route = 'admin/coupons';
	protected static $views = 'admin/coupons';

	protected $visible = [
		'code',
		'type',
		'value',
	];

	// how much to pay for the order
	public function check(Request $request)
	{
		$coupon_code = trim($request->get('coupon'));

		$coupon = Coupon::getCoupon($coupon_code);
		if (!$coupon) {
			$coupon = new Coupon;
			$coupon->value = 0;
		}

		$order = Order::getUnpaidOrder();
		$total = $order->clientPrice($coupon_code);
		$subtotal = $order->subtotal();



		return [
			'total' => $total,
			'coupon_type' => $coupon->type,
			'coupon_value' => $coupon->value,
			'subtotal' => $subtotal,
			'you_save' => $subtotal - $total,
		];
	}

	public function store(Request $request)
	{
		$coupon = new Coupon();
		$input = $request->all();
		if (trim($input['expires_at'] == '')) {
			$input['expires_at'] = null;
		}
		$coupon->fill($input);
		$coupon->save();



		return redirect('/' . self::$route);
	}

	public function update(Request $request, $coupon_id)
	{
		$coupon = Coupon::findOrFail($coupon_id);
		$input = $request->all();
		if (trim($input['expires_at'] == '')) {
			$input['expires_at'] = null;
		}
		$coupon->fill($input);
		$coupon->save();

		return redirect('/' . self::$route);
	}


}
