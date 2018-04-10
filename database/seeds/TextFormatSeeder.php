<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\TextFormat;

class TextFormatSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		TextFormat::create([
			'slug' => 'clean_verbatim',
			'name' => 'Clean verbatim',
			'client_price_per_minute' => 0,
		]);
		TextFormat::create([
			'slug' => 'full_verbatim',
			'name' => 'Full verbatim',
			'client_price_per_minute' => 0.25,
		]);
	}

}