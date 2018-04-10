<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingDelaysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rating_delays', function(Blueprint $table)
		{
			$table->engine = "InnoDB";

			$table->increments('id');
			$table->integer('rating_till')->unique()->unsigned(); // 1 = 0-1
			$table->integer('delay'); // seconds
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
		Schema::drop('rating_delays');
	}

}
