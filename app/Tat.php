<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Tat extends Model {

	protected $fillable = [
		'days',
		'client_price_per_minute',
		'editor_price_per_minute',
		'slice_duration',
		'max_transcription_time',
	];

}
