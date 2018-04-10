<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Coupon;

class CouponSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// amount
		Coupon::create([
			'code' => '10dollars',
			'type' => 'amount',
			'value' => 10,
			'comment' => 'kuponas 1',
		]);

		// percent
		Coupon::create([
			'code' => '10percent',
			'type' => 'percent',
			'value' => 10,
			'comment' => 'kuponas 2',
		]);

		// one usage
		Coupon::create([
			'code' => 'onetime',
			'type' => 'percent',
			'value' => 10,
			'single_use' => 1,
		]);

		// expired
		Coupon::create([
			'code' => 'expired',
			'type' => 'percent',
			'value' => 10,
			'expires_at' => '2000-01-01 01:01:01',
		]);
	}

}