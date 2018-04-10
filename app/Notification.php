<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

	// --------------------------------------- scopes ------------------------------------------------------------------
	public function scopeNotSeen($query)
	{
		$query->where('seen', 0);
	}

}
