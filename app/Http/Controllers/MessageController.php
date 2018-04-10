<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Message;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class MessageController extends Controller {

	public function store($recipient_id) {
		// does recipeitn exists
		User::findOrFail($recipient_id);

		if (!Input::has('content')) {
			return redirect()->back();
		}


		$message = new Message;
		$message->sender_id = Auth::id();
		$message->recipient_id = $recipient_id;
		$message->content = Input::get('content');
		$message->save();

		return redirect()->back();
	}

	public function showChatWith($sender_id) {
		Message::setSeen($sender_id);

		$messages = Message::chatWith($sender_id)->orderBy('created_at', 'desc')->paginate(6);
		$messages->load('sender', 'recipient');

		$recipient = User::findOrFail($sender_id);

		return view('messages.show_chat_with', compact('messages', 'recipient'));
	}

}
