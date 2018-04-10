<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Discount;

class DiscountSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Discount::create([
			'percent' => 10,
			'minutes' => 180,
		]);
		Discount::create([
			'percent' => 15,
			'minutes' => 360,
		]);
		Discount::create([
			'percent' => 20,
			'minutes' => 720,
		]);
	}

}