<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Message;

class MessageSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$message = new Message;
		$message->sender_id = 2;
		$message->recipient_id = 1;
		$message->content = 'pirma zinute';
		$message->save();

		$message = new Message;
		$message->sender_id = 1;
		$message->recipient_id = 2;
		$message->content = 'antra zinute';
		$message->save();
	}

}