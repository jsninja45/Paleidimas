<?php namespace App\Observers;

use App\Chat;

class MessageObserver {

	public function saving($model)
	{
		
	}

	public function saved($model)
	{
		Chat::newMessage($model->sender_id, $model->recipient_id, $model->id);
	}

}