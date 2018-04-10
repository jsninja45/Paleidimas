<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model {

	use SoftDeletes;

	protected $fillable = [
		'name',
		'rating',
		'content',
	];

	public static $rules = [
		'name' => 'required',
		'rating' => 'required|integer|between:1,5',
		'content' => 'required',
	];

	public function link($type)
	{
		if ($type === 'confirm') {
			return '/admin/reviews/' . $this->id . '/confirm';
		}
		if ($type === 'delete') {
			return '/admin/reviews/' . $this->id . '/delete';
		}

		echo('wrong parameter'); die(' ' . __FILE__ . ':' . __LINE__);
	}
}
