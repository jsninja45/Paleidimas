<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use App\Order;

class Calculator extends Model {


	// data for calculator view
	public static function viewData()
	{
		if (Auth::check()) {
			$user = Auth::user();
		} else {
			$user = new User;
		}
		$order = Order::getUnpaidOrder();
		$order->load('textFormat', 'timestamping', 'speaker', 'tat');
		$text_formats = TextFormat::all();
		$timestampings = Timestamping::all();
		$speakers = Speaker::all();
		$tats = Tat::all();
		$languages = Language::notHidden()->get();

		return compact('order', 'user', 'text_formats', 'timestampings', 'speakers', 'tats', 'languages');
	}

}
