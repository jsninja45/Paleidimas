<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model {

	protected $table = 'role_user';

	public static function addRole(User $user, Role $role)
	{
		if ($user->hasRole($role->name)) {
			return false;
		}

		$role_user = new RoleUser();
		$role_user->user_id = $user->id;
		$role_user->role_id = $role->id;
		$role_user->save();
	}

}
