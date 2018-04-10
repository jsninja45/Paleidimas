<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Tat;

class TatSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Tat::create([
			'slug' => 'days_1',
			'days' => 1,
			'client_price_per_minute' => 2,
			'editor_price_per_minute' => 0.6,
			'transcriber_price_per_minute' => 0.8,
			'slice_duration' => 60 * 1, // 1 minute
			'max_transcription_duration' => 3600 * 2, // 2 hours
		]);
		Tat::create([
			'slug' => 'days_3',
			'days' => 3,
			'client_price_per_minute' => 1.6,
			'editor_price_per_minute' => 0.5,
			'transcriber_price_per_minute' => 0.7,
			'slice_duration' => 60 * 2,
			'max_transcription_duration' => 3600 * 3,
		]);
		Tat::create([
			'slug' => 'days_7',
			'days' => 7,
			'client_price_per_minute' => 1.2,
			'editor_price_per_minute' => 0.4,
			'transcriber_price_per_minute' => 0.6,
			'slice_duration' => 60 * 3,
			'max_transcription_duration' => 3600 * 12,
		]);
		Tat::create([
			'slug' => 'days_14',
			'days' => 14,
			'client_price_per_minute' => 0.8,
			'editor_price_per_minute' => 0.3,
			'transcriber_price_per_minute' => 0.4,
			'slice_duration' => 60 * 4,
			'max_transcription_duration' => 3600 * 24,
		]);
	}

}