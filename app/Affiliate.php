<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Affiliate extends Model {



	// new user is send from affiliate
	public static function setCookie($request)
	{
		if (self::redirectedFromAffiliate($request)) {
			setcookie('affliate_abovealloffers', $_SERVER['QUERY_STRING'], time() + (3600 * 24 * 30), '/');

			//header('Location: /');
			//exit;
			return true;
		} else {
			return false;
		}
	}

	public static function userLoggedIn(User $user) // sign up
	{
		// check if we need to ping for the first time affiliate
		if (isset($_COOKIE['affliate_abovealloffers']) && !$user->affliate_abovealloffers) {

			if ($user->created_at < date('Y-m-d H:i:s', strtotime('-1 minute'))) { // if old registered user came through affiliate link, we wont register it as affiliate user
				setcookie('affliate_abovealloffers', null, -1, '/'); // delete cookie

				return false;
			}


			$user->affliate_abovealloffers = $_COOKIE['affliate_abovealloffers'];
			$user->save();

			setcookie('affliate_abovealloffers', null, -1, '/'); // delete cookie

			$data = self::affiliateData($user);
			$transaction_id = $user->id;
			$request_session_id = $data['r'];


			$url = 'https://aboveallurl.com/p.ashx?o=4853&e=516&f=pb&r=' . $request_session_id . '&t=' . $transaction_id;
			Log::info($url);
			if (env('SEND_INFO_TO_AFFILIATE', false)) {
				$response = file_get_contents($url);

				if (stristr($response, 'SUCCESS') === false) {
					Log::error('something wrong with affliate - ' . $url, (array)$response);
					return false;
				}
			}



			return true;
		}
	}

	public static function userPurchased(Order $order)
	{
		if (!$order->user->affliate_abovealloffers) {
			return false;
		}

		$data = self::affiliateData($order->user);

		$request_session_id = $data['r'];
		$total_order_price = $order->client_price;
		$transaction_id = $order->id;

		$url = 'https://aboveallurl.com/p.ashx?o=4853&e=515&f=pb&r=' . $request_session_id . '&t=' . $transaction_id . '&p=' . $total_order_price;
		Log::info($url);
		if (env('SEND_INFO_TO_AFFILIATE', false)) {
			$response = file_get_contents($url);

			if (stristr($response, 'SUCCESS') === false) {
				Log::error('something wrong with affliate - ' . $url, (array)$response);
				return false;
			}
		}
	}


	private static function redirectedFromAffiliate($request){
		if ($request->has('a') && $request->has('r')) {
			return true;
		} else {
			return false;
		}
	}



	private static function affiliateData($user)
	{
		// a - affiliate id
		// 4 - request session id

		$data = null;
		parse_str($user->affliate_abovealloffers, $data);

		if (!isset($data['a']) || !isset($data['r'])) {
			return null;
		} else {
			return $data;
		}

	}
}
