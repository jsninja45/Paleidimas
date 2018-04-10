<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDrupalMigrationColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
		{
            // username column
			$table->string('name', 60)->nullable()->unique()
				->comment('Used only for old users log in, new user can login only via email')->change();

            $table->string('old_password', 128)->nullable()->default(NULL);
            $table->boolean('full_discount')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
