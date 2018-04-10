<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Audio;
use Illuminate\Support\Facades\Auth;

class SubtitleJobController extends Controller {

	public function available() {
		$user = Auth::user();
		$audios = Audio::availableForSubtitling($user)->lateFirst()->get();
		$audios->load('order.textFormat', 'order.timestamping', 'order.language', 'order.subtitle');
		$total = count($audios);

		return view('subtitling_jobs.available', compact('audios', 'user', 'total'));
	}

	public function in_progress()
	{
		$user = Auth::user();

		$audios = $user->subtitlerAudios()->inSubtitling()->get();
		$audios->load('order.textFormat', 'order.timestamping', 'order.language', 'order.subtitle');

		return view('subtitling_jobs.in_progress', compact('audios', 'user'));
	}

	public function finished()
	{
		$user = Auth::user();

		$audios = $user->subtitlerAudios()->finished()->get();
		$audios->load('order.textFormat', 'order.timestamping', 'order.language', 'order.subtitle');

		return view('subtitling_jobs.finished', compact('audios', 'user'));
	}

}
