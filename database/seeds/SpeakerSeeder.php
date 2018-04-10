<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Speaker;

class SpeakerSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Speaker::create([
			'slug' => 'max_2',
			'name' => '1-2',
			'client_price_per_minute' => 0,
		]);
		Speaker::create([
			'slug' => 'min_3',
			'name' => '3 or more',
			'client_price_per_minute' => 0.2,
		]);
	}

}