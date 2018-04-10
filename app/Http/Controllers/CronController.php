<?php namespace App\Http\Controllers;

use App\Email;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Cron;

class CronController extends Controller {

	// this method should be called every minute
	public function index()
	{
        // every hour
        self::hourly(function() {
            Cron::takeAudioSlicesAway(); // if transcriber is working on them too long
            Cron::takeAudiosAway(); // if editor working on file too long
            Cron::unpaidOrderReminder(); // after order created 24 and 72 hours
            Cron::unpaidOrder7Days(); // delete and send email
        });

		self::everySaturday(function() {
			Cron::deleteFinishedFiles(); // after 2 month
			Email::paymentsAreGenerated();
		});

		Cron::queuedJobs();
	}

	public static function hourly($callback)
	{
		if (date('i') == 0) {
			$callback();
		}
	}

	public static function everySaturday($callback)
	{
        if (date('w') == 6 && date('H') == 0 && date('i') == 0) {
			$callback();
		}
	}


}
