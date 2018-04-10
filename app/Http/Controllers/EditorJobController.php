<?php namespace App\Http\Controllers;

use App\AudioSlice;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Audio;

class EditorJobController extends Controller {

	public function available() {
		$user = Auth::user();
		$audios = Audio::availableForUser($user)->lateFirst()->get();
		$audios->load('order.textFormat', 'order.timestamping', 'order.language');
		$total = count($audios);

		return view('editing_jobs.available', compact('audios', 'user', 'total'));
	}

	public function in_progress()
	{
		$user = Auth::user();

		$audios = $user->editorAudios()->inEditing()->get();
		$audios->load('slices.transcription', 'order.textFormat', 'order.timestamping', 'order.language');

		return view('editing_jobs.in_progress', compact('audios', 'user'));
	}

	public function finished()
	{
		$user = Auth::user();

		$audios = $user->editorAudios()->finishedEditing()->get();
		$audios->load('order.textFormat', 'order.timestamping', 'order.language');

		return view('editing_jobs.finished', compact('audios', 'user'));
	}


}
