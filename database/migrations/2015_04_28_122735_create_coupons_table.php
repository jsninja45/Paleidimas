<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupons', function(Blueprint $table)
		{
			$table->engine = "InnoDB";

			$table->increments('id');
			$table->string('code')->unique();
			$table->enum('type', ['amount', 'percent'])->default('amount');
			$table->decimal('value');
			$table->text('comment');

			$table->timestamp('expires_at')->nullable();
			$table->boolean('single_use'); // same user can use coupon only one time

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
		Schema::drop('coupons');
	}

}
