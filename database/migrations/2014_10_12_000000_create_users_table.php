<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->engine = "InnoDB";

			$table->increments('id');
			$table->rememberToken();
			$table->string('name');
			$table->string('email')->unique();
			$table->string('second_email')->nullable();
			$table->string('password', 60);
			$table->string('social_login_type'); // facebook, google, twitter...
			$table->string('social_login_id');
			$table->string('paypal_email'); // for payments (transcriber and editor)
			$table->integer('job_limit')->default(1);
			$table->boolean('newsletter')->default(1); // send or not user newsletters
			$table->boolean('deleted'); // if deleted, user can't login

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
		Schema::drop('users');
	}

}
