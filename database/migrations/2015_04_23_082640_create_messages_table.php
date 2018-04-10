<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('messages', function(Blueprint $table)
		{
			$table->engine = "InnoDB";
			$table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');

			$table->increments('id');
			$table->integer('sender_id')->unsigned();
			$table->integer('recipient_id')->unsigned();
			$table->boolean('seen');
			$table->text('content');
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
		Schema::drop('messages');
	}

}
