<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudioSubtitlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audio_subtitles', function(Blueprint $table)
		{
			$table->engine = "InnoDB";
			$table->foreign('audio_id')->references('id')->on('audios')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

			$table->increments('id');
			$table->integer('audio_id')->unsigned()->index();
			$table->integer('user_id')->unsigned(); // because audio slice can be taken by different user if current cancels
			$table->text('filename');
//			$table->integer('rating')->nullable()->default(null);
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
		Schema::drop('audio_subtitles');
	}

}
