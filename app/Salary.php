<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Salary extends Model {

	// how much we need to pay to user for this period
	public static function amount($user, $from = null, $till = null, $just = null) // salary for passed week (or more, if not paid before)
	{
		if ($from) {
			$from = date('Y-m-d 00:00:00', strtotime($from));
		} else {
			$from = Salary::from($user);
		}
		if ($till) {
			$till = date('Y-m-d 23:59:59', strtotime($till));
		} else {
			$till = Salary::till($user);
		}

		if ($from > $till) {
			Log::error('wrong dates', [$from, $till]);
			die('error: error with dates');
		}

		// check if we allready paid
		$last_payment_to_user = self::lastPaymentToUser($user);
		if ($last_payment_to_user) {
			if ($last_payment_to_user->worker_payment_from === $from && $last_payment_to_user->worker_payment_till === $till) { // same period
				return 0; // we already paid
			} elseif ($last_payment_to_user->worker_payment_till > $from) {
				Log::error('wrong dates 2', [$from, $till, $user]);
				die('error: error with dates 2');
			}
		}


		$editor_amount = $user->editorAudios()->where('editor_price', '>', 0)->where('finished_at', '>=', $from)->where('finished_at', '<=', $till)->sum('editor_price');
		$transcriber_amount = $user->transcriberAudioSlices()->where('transcriber_price', '>', 0)->where('finished_at', '>=', $from)->where('finished_at', '<=', $till)->sum('transcriber_price');
		$subtitler_amount = $user->subtitlerAudios()->where('subtitler_price', '>', 0)->where('finished_at', '>', $from)->where('finished_at', '<=', $till)->sum('subtitler_price');
		$bonus_amount = $user->bonuses()->where('created_at', '>', $from)->where('created_at', '<=', $till)->sum('amount');

		if ($just === 'bonus') {
			return $bonus_amount;
		}

		$amount = $editor_amount + $transcriber_amount + $subtitler_amount + $bonus_amount;

		return $amount;
	}

	// from last week or earlier
	public static function from($user) // including
	{
		$till = self::till($user);
		$from = date('Y-m-d H:i:s', strtotime($till . ' -1 week') + 1);

		$older_payment = self::olderPayment($user, $from);
		if ($older_payment) {
			$from = date('Y-m-d H:i:s', strtotime($older_payment->worker_payment_till) + 1);
		} else {
			$from = '2000-01-01 00:00:00';
		}

		return $from;
	}

	public static function till($user) // including
	{
		$till = date('Y-m-d 23:59:59', strtotime("last Friday")); // 2015-06-12 23:59:59 - including

		return $till;
	}

	private static function lastPaymentToUser($user)
	{
		$last_payment_to_user = Transaction::where('type', 'worker')->where('user_id', $user->id)->orderBy('worker_payment_till', 'desc')->first();

		return $last_payment_to_user;
	}

	private static function olderPayment($user, $older_than_date)
	{
		return Transaction::where('type', 'worker')->where('user_id', $user->id)->where('worker_payment_till', '<', $older_than_date)->orderBy('worker_payment_till', 'desc')->first();
	}

	// -----------------------------------------------------------------------------------------------------------------

	// salary from last saturday
	public static function earnedTillNow($user) // what would i do that, worker can be not paid for several weeks?
	{
		$from = self::earnedTillNowFrom($user);
		$till = date('Y-m-d H:i:s'); // now

		return self::amount($user, $from, $till);
	}

	public static function earnedTillNowFrom($user)
	{
		$older_payment = self::olderPayment($user, date('Y-m-d H:i:s')); // older than now
		if ($older_payment) {
			$from = date('Y-m-d H:i:s', strtotime($older_payment->worker_payment_till) + 1);
		} else {
			$from = self::from($user);
		}

		return $from;
	}

}
