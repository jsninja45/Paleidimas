<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Timestamping;

class TimestampingSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Timestamping::create([
			'slug' => 'not_required',
			'name' => 'Not required',
			'client_price_per_minute' => 0,
			'editor_price_per_minute' => 0,
		]);
		Timestamping::create([
			'slug' => 'every_2_minutes',
			'name' => 'Every 2 minutes',
			'client_price_per_minute' => 0.5,
			'editor_price_per_minute' => 0.1,
		]);
		Timestamping::create([
			'slug' => 'change_of_speaker',
			'name' => 'Change of speaker',
			'client_price_per_minute' => 0.5,
			'editor_price_per_minute' => 0.1,
		]);
	}

}