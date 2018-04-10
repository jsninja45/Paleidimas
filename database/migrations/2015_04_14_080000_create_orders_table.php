<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function(Blueprint $table)
		{
			$table->engine = "InnoDB";
			$table->foreign('client_id')->references('id')->on('users');//->onDelete('cascade');

			$table->increments('id');
			$table->integer('client_id')->unsigned()->nullable()->index(); // user id
			$table->timestamp('paid_at')->nullable();
			$table->boolean('finished');
			$table->integer('language_id')->unsigned();
			$table->integer('speaker_id')->unsigned();
			$table->integer('text_format_id')->unsigned();
			$table->integer('timestamping_id')->unsigned();
			$table->integer('tat_id')->unsigned(); // turn around time
			$table->integer('coupon_id')->unsigned()->nullable();
			$table->decimal('client_price')->nullable();
			$table->timestamp('deadline_at')->nullable();
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
		Schema::drop('orders');
	}

}
