<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudioSliceLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audio_slice_logs', function(Blueprint $table)
		{
			$table->engine = "InnoDB";

			$table->increments('id');
			$table->integer('slice_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->enum('action', ['take', 'cancel', 'finish', 'deadline_miss']); // cancel - didn't wanted to translate, deadline_miss - file was taken from user
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
		Schema::drop('audio_slice_logs');
	}

}
