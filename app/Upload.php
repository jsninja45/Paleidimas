<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Upload extends Model {

	public static function assignOrderToUser($anonymous_order)
	{
		// delete if user had unpaid orders while he was logged in
		$user = Auth::user();
		if ($anonymous_order->audios->count()) {
			$unpaid_orders = $user->orders()->onlyUnpaid()->get();
			foreach ($unpaid_orders as $order) {
				$order->delete();
			}
			$anonymous_order->client_id = $user->id;
			$anonymous_order->save();
		} else {
			$anonymous_order->delete();
		}

		return true;
	}

}
