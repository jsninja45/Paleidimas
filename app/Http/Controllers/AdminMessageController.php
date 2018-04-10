<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Message;
use App\User;
use Illuminate\Http\Request;

// show all chats between users
class AdminMessageController extends Controller {

    public function index(Request $request)
    {
        $messages = Message::urlFilter($request)->orderBy('created_at', 'desc')->paginate(20);
        $messages->load('recipient', 'sender');
//        $users = User::get();

        return view('admin.messages.index', compact('messages', 'users'));
    }

}