<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Timestamping;

class RatingDelaySeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$values = [
			[1, 1440],
			[1.5, 1200],
			[2, 900],
			[2.5, 600],
			[3, 300],
			[3.5, 120],
			[4, 60],
			[4.5, 0],
		];

		foreach ($values as $value) {
			\App\RatingDelay::create([
				'rating_till' => $value[0],
				'delay' => $value[1],
			]);

		}
	}

}