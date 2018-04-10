<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRatingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_ratings', function(Blueprint $table)
		{
			$table->engine = "InnoDB";
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');

			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('language_id')->unsigned();
			$table->decimal('rating', 8, 1);
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
		Schema::drop('user_ratings');
	}

}
