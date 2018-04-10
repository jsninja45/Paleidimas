<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTatsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tats', function(Blueprint $table) // how long till translation deadline after user submitted the order (turn around time)
		{
			$table->engine = "InnoDB";

			$table->increments('id');
			$table->string('slug');
			$table->integer('days');
			$table->decimal('client_price_per_minute', 10, 4);
			$table->decimal('editor_price_per_minute', 10, 4);
			$table->decimal('transcriber_price_per_minute', 10, 4);
			$table->integer('slice_duration'); // seconds, make slices this length
			$table->integer('max_transcription_duration'); // seconds, how much time user has to translate file before it is taken away
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tats');
	}

}
