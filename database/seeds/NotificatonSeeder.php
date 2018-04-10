<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Notification;

class NotificationSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Notification::create([
			'user_id' => 1,
			'title' => 'order completed',
			'link' => 'google.com',
		]);
		Notification::create([
			'user_id' => 1,
			'title' => 'files converted',
			'link' => 'google.com',
		]);
		Notification::create([
			'user_id' => 1,
			'title' => 'order completed',
			'link' => 'google.com',
		]);
	}

}