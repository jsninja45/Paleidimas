<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Timestamping extends Model {

	protected $fillable = [
		'name',
		'client_price_per_minute',
		'editor_price_per_minute',
	];

}
