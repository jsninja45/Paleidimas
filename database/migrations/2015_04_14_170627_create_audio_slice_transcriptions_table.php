<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudioSliceTranscriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audio_slice_transcriptions', function(Blueprint $table)
		{
			$table->engine = "InnoDB";
			$table->foreign('audio_slice_id')->references('id')->on('audio_slices')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

			$table->increments('id');
			$table->integer('audio_slice_id')->unsigned()->index();
			$table->integer('user_id')->unsigned(); // because audio slice can be taken by different user if current cancels
			$table->text('filename');
			//$table->integer('rating')->nullable()->default(null);
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
		Schema::drop('audio_slice_transcriptions');
	}

}
