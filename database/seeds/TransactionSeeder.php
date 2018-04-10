<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Transaction;

class TransactionSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Transaction::payToUser(1, '2010-01-01', '2010-12-31', 13.43);
		Transaction::payToUser(1, '2012-01-01', '2012-12-31', 17);
		Transaction::payToUser(1, '2013-01-01', '2013-12-31', 0.43);
	}


}