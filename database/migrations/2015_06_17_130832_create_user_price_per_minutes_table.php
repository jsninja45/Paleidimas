<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPricePerMinutesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_price_per_minutes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->unique()->unsigned();

			$table->decimal('transcriber_price_1_tat', 10, 8)->nullable()->comment('perice per minute');
			$table->decimal('transcriber_price_3_tat', 10, 8)->nullable()->comment('perice per minute');
			$table->decimal('transcriber_price_7_tat', 10, 8)->nullable()->comment('perice per minute');
			$table->decimal('transcriber_price_14_tat', 10, 8)->nullable()->comment('perice per minute');

			$table->decimal('editor_price_1_tat_no_timestamping', 10, 8)->nullable()->comment('perice per minute');
			$table->decimal('editor_price_3_tat_no_timestamping', 10, 8)->nullable()->comment('perice per minute');
			$table->decimal('editor_price_7_tat_no_timestamping', 10, 8)->nullable()->comment('perice per minute');
			$table->decimal('editor_price_14_tat_no_timestamping', 10, 8)->nullable()->comment('perice per minute');
			$table->decimal('editor_price_1_tat_with_timestamping', 10, 8)->nullable()->comment('perice per minute');
			$table->decimal('editor_price_3_tat_with_timestamping', 10, 8)->nullable()->comment('perice per minute');
			$table->decimal('editor_price_7_tat_with_timestamping', 10, 8)->nullable()->comment('perice per minute');
			$table->decimal('editor_price_14_tat_with_timestamping', 10, 8)->nullable()->comment('perice per minute');
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
		Schema::drop('user_price_per_minutes');
	}

}
