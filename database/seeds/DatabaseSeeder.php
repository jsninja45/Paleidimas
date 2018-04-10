<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		$this->call('TimestampingSeeder');
		$this->call('TatSeeder');
		$this->call('TextFormatSeeder');
		$this->call('RoleSeeder');
		$this->call('LanguageSeeder');
		$this->call('CouponSeeder');
		$this->call('RatingDelaySeeder');
		$this->call('SpeakerSeeder');
		$this->call('ArticleSeeder');
		$this->call('ReviewSeeder');
		$this->call('DiscountSeeder');
		$this->call('FAQSeeder');
		$this->call('NotificationSeeder');
		$this->call('ConfigSeeder');
		$this->call('ServiceSeeder');

		$this->call('UserSeeder');
		$this->call('UserRatingSeeder');
//		$this->call('OrderSeeder');
		$this->call('TransactionSeeder');
		$this->call('MessageSeeder');
	}

}
