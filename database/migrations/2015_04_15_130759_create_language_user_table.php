<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('language_user', function(Blueprint $table)
		{
			$table->engine = "InnoDB";
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');

			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('language_id')->unsigned();
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
		Schema::drop('language_user');
	}

}
