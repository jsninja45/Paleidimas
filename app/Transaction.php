<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Transaction extends Model {


	// --------------------------------------- scopes  -----------------------------------------------------------------

	public function scopeReceived($query)
	{
		$query->where('type', 'order');
	}

	public function scopePaid($query)
	{
		$query->where('type', 'worker');
	}


	// --------------------------------------- methods - data manipulation ---------------------------------------------

	public static function receiveForOrder($order_id, $user_id, $amount)
	{
		$transaction = new Transaction();
		$transaction->order_id = $order_id;
		$transaction->user_id = $user_id;
		$transaction->amount = $amount;
		$transaction->type = 'order';
		$transaction->save();

		return $transaction;
	}

	public static function payToUser($user_id, $from, $till, $amount)
	{
		$from = date('Y-m-d 00:00:00', strtotime($from));
		$till = date('Y-m-d 23:59:59', strtotime($till));

        if ($from > $till) {
            Log::error('error: wrong dates 3');
            die('error: wrong dates 3');
        }

        $transaction = new Transaction();
		$transaction->user_id = $user_id;
		$transaction->worker_payment_from = $from;
		$transaction->worker_payment_till = $till;
		$transaction->amount = -$amount; // negative
		$transaction->type = 'worker';
		$transaction->save();

		return $transaction;
	}

	// --------------------------------- methods - view data -----------------------------------------------------------

	public function paymentDetails($user_id, $from_date = null, $till_date = null)
	{



	}

}
