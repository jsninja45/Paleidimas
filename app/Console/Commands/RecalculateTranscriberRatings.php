<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;
use App\UserRating;

class RecalculateTranscriberRatings extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'recalculate-ratings';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $transcribers = User::transcriber()->lists('id');

        foreach ($transcribers as $transcriber_id) {
            UserRating::recalculateAll($transcriber_id);
        }
    }

}
