<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\UserRating;

class UserRatingSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		UserRating::where('user_id', 1)->update([
			'rating' => 5,
		]);
	}




}