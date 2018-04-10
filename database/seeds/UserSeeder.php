<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Role;

class UserSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// admin
		$user = User::create([
			'email' => 'admin@admin.com',
			'password' => 'admin',
			'job_limit' => 2,
		]);

		$role_names = Role::lists('name'); // all
		foreach ($role_names as $role_name) {
			$user->addRole($role_name);
		}

		$language_ids = App\Language::lists('id');
		$user->languages()->sync($language_ids); // all languages
		\App\UserRating::recalculateAll($user->id);




		// other users
		$this->createUser('editor');
		$this->createUser('transcriber');
		$this->createUser('client');
		$this->createUser('subtitler');


		// deleted
		$user = User::create([
			'email' => 'deleted@deleted.com',
			'password' => 'deleted',
			'deleted' => 1,
		]);

		$role_names = Role::lists('name'); // all
		foreach ($role_names as $role_name) {
			$user->addRole($role_name);
		}


		$language_ids = App\Language::lists('id');
		$user->languages()->sync($language_ids); // all languages
		\App\UserRating::recalculateAll($user->id);


	}

	private function createUser($name) {
		$user = User::create([
			'email' => $name . '@' . $name . '.com',
			'password' => $name,
		]);

		$user->addRole($name);

		$user->languages()->sync([1]); // english
		\App\UserRating::recalculateAll($user->id);
	}

}