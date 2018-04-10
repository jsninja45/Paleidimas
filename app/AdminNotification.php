<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model {

	public static function add($content, $link = '')
	{
		$notification = new AdminNotification();
		$notification->content = $content;
		$notification->link = $link;
		$notification->save();

		return true;
	}

}
