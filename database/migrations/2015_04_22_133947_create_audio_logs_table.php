<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudioLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audio_logs', function(Blueprint $table)
		{
			$table->engine = "InnoDB";

			$table->increments('id');
			$table->integer('audio_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->enum('action', ['take', 'cancel', 'finish']); // canceled - didn't wanted to translate
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
		Schema::drop('audio_logs');
	}

}
