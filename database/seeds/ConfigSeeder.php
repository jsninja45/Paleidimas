<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Config;

class ConfigSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Config::create([
			'name' => 'slice_no_rating_delay',
			'value' => 3,
			'comment' => 'How long transcriber without rating must wait before he can take file for transcription',
		]);
	}

}