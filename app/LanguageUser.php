<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class LanguageUser extends Model {

	public function boot(DispatcherContract $events)
	{
		parent::boot($events);

		LanguageUser::created(function($model)
		{
			UserRating::recalculate($model->user_id);
		});

		LanguageUser::deleted(function($model)
		{
			UserRating::recalculate($model->user_id);
		});
	}


}
