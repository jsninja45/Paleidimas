<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Subtitle;

class SeedSubtitler extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\Illuminate\Database\Eloquent\Model::unguard();

		Subtitle::create([
			'slug' => 'not_required',
			'name' => 'Not required',
			'client_price_per_minute' => 0,
			'subtitler_price_per_minute' => 0,
		]);
		Subtitle::create([
			'slug' => 'srt',
			'name' => 'SRT',
			'client_price_per_minute' => 0.4,
			'subtitler_price_per_minute' => 0.2,
		]);
		Subtitle::create([
			'slug' => 'txt',
			'name' => 'TXT',
			'client_price_per_minute' => 0.4,
			'subtitler_price_per_minute' => 0.2,
		]);

		\Illuminate\Database\Eloquent\Model::reguard();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
