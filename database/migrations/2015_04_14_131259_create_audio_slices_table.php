<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudioSlicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audio_slices', function(Blueprint $table)
		{
			$table->engine = "InnoDB";
			$table->foreign('audio_id')->references('id')->on('audios')->onDelete('cascade');

			$table->increments('id');
			$table->integer('audio_id')->unsigned()->index();
			$table->enum('status', ['available', 'in_progress', 'finished'])->default('available');
			$table->integer('from');
			$table->integer('till');
			$table->integer('transcriber_id')->unsigned()->nullable();
			$table->decimal('transcriber_price')->nullable();
			$table->integer('rating')->nullable()->unsigned()->default(null);
			$table->text('editor_comment')->nullable();
			$table->timestamp('deadline_at')->nullable();
			$table->timestamp('finished_at')->nullable();
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
		Schema::drop('audio_slices');
	}

}
