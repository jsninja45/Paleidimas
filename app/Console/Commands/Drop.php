<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Drop extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'drop';


	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$tables = [];

		DB::statement( 'SET FOREIGN_KEY_CHECKS=0' );

		foreach (DB::select('SHOW TABLES') as $k => $v) {
			$tables[] = array_values((array)$v)[0];
		}

		foreach($tables as $table) {
			Schema::drop($table);
			echo "Table ".$table." has been dropped.".PHP_EOL;
		}


		$this->call('migrate', array('--seed' => true));

	}

}
