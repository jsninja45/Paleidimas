<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudiosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('audios', function(Blueprint $table)
		{
			$table->engine = "InnoDB";
			$table->foreign('order_id')->references('id')->on('orders'); // doesnt delete automatically
//			$table->foreign('tat_id')->references('id')->on('tats');
//			$table->foreign('language_id')->references('id')->on('languages');
			//$table->foreign('transcription_type_id')->references('id')->on('transcription_types');
			//$table->foreign('timestamping_id')->references('id')->on('timestampings');

			$table->increments('id');
			$table->integer('order_id')->index()->unsigned();
			$table->enum('status', ['unpaid', 'in_transcription', 'available_for_editing', 'in_editing', 'finished'])->default('unpaid');
			$table->string('original_filename')->nullable();
			$table->integer('original_duration');
			$table->integer('size'); // megabytes
			$table->integer('from'); // transcribe from
			$table->integer('till');
			$table->text('comment')->comment('When client uploads file, he can write comment'); // client comment
			$table->integer('editor_id')->unsigned();
			$table->timestamp('deadline_at')->nullable()->default(null);
			$table->integer('rating')->nullable()->default(null);
			$table->text('rating_comment')->nullable();
			$table->string('url')->nullable(); // if youtube or vimeo video
			$table->decimal('client_price')->nullable();
			$table->decimal('editor_price')->nullable();
			$table->timestamp('finished_at')->nullable()->default(null);
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
		Schema::drop('audios');
	}

}
