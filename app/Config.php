<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model {

	public static function get($name, $full = false)
	{
		if ($full) {
			return Config::where('name', $name)->first();
		} else {
			return Config::where('name', $name)->first()->value;
		}
	}

	public static function set($name, $value, $comment = '')
	{
		$config = Config::get($name, true);
		if (!$config) {
			$config = new Config();
		}

		$config->name = $name;
		$config->value = $value;
		$config->comment = $comment;
		$config->save();
	}

}
