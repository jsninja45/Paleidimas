<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Cron extends Model {

	// if transcriber is working on them to long
	public static function takeAudioSlicesAway()
	{
		$audio_slices = \App\AudioSlice::missedDeadline()->get();
		foreach ($audio_slices as $slice) {
			$slice->makeAvailableAgain();
		}
	}

	// when user register after 24 hours we need to remind about unpaid files
	public static function unpaidOrderReminder()
	{
		// 24 hours
		$orders = Order::created24HoursAgo()->unpaid()->has('audios')->has('user')->get();
		foreach ($orders as $order) {
			Email::clientUnpaidOrderReminder($order);
		}

		// 72 hours
		$orders = Order::created72HoursAgo()->unpaid()->has('audios')->has('user')->get();
		foreach ($orders as $order) {
			Email::clientUnpaidOrderReminder($order);
		}
	}

	public static function unpaidOrder7Days()
	{
		// send email
		$orders = Order::created72HoursAgo()->unpaid()->has('audios')->has('user')->get();
		foreach ($orders as $order) {
			Email::clientUnpaidOrder7Days($order);
		}

		// delete orders
		$orders = Order::created7DaysAgo()->unpaid()->get();
		foreach ($orders as $order) {
			foreach ($order->audios as $audio) {
				$audio->delete();
			}
			$order->delete();
		}
	}

	// delete order files after 2 months
	public static function deleteFinishedFiles()
	{
		$audios = Audio::whereHas('order', function($q) {
			$q->finished()->where('paid_at', '<', date('Y-m-d H:i:s', strtotime('-2 month')));
		});
		foreach ($audios as $audio) {
			$audio->deleteFile();
		}
	}

	public static function takeAudiosAway()
	{
		$audios = \App\Audio::editorMissedDeadline()->get();
		foreach ($audios as $audio) {
			$audio->makeAvailableAgain();
		}
	}

	// scheduled jobs
	public static function queuedJobs()
	{
		$start_time = time();

		// run while there is jobs and if it is running no longer that 15 seconds
		while (Job::count() && ((time() - $start_time) < 15)) {
			\Illuminate\Support\Facades\Artisan::call('queue:work');
		}
	}
}
