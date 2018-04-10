<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Role;
use App\User;


class RoleSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Role::create([
			'name' => 'admin',
		]);
		Role::create([
			'name' => 'editor',
		]);
		Role::create([
			'name' => 'transcriber',
		]);
		Role::create([
			'name' => 'client',
		]);
	}

}