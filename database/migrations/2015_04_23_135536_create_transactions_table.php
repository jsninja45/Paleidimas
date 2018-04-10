<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function(Blueprint $table)
		{
			$table->engine = "InnoDB";
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

			$table->increments('id');
			$table->enum('type', ['order', 'worker'])->nullable(); // received money for order, or paid to worker
			$table->decimal('amount');
			$table->integer('order_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->datetime('worker_payment_from')->nullable(); // from >=
			$table->datetime('worker_payment_till')->nullable(); // till < (not including that date)
			$table->enum('payment_type', ['paypal', 'creditcard'])->nullable();
			$table->string('payment_email')->nullable();
			$table->string('payment_creditcard', 4)->nullable(); // last 4 digits of credit card
			$table->string('payment_name')->nullable();
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
		Schema::drop('transactions');
	}

}
