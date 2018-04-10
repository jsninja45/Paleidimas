<?php namespace App\Http\Controllers;

use App\Email;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\UserRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\AudioSlice;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class AudioSliceController extends Controller {

//	public function edit_comment($slice_id)
//	{
//		$user = Auth::user();
//
//		if (!$user->audioSlices()->where('id', $slice_id)->exists()) {
//			abort('401', "This user can't edit comment");
//		}
//
//		$slice = AudioSlice::findOrFail($slice_id);
//		$comment = $slice->comment;
//
//		return view('transcription_jobs.edit_comment', compact('comment'));
//	}
//
//
//	public function store_comment($slice_id)
//	{
//		$user = Auth::user();
//
//		if (!$user->audioSlices()->where('id', $slice_id)->exists()) {
//			abort('401', "This user can't edit comment");
//		}
//
//		$slice = AudioSlice::findOrFail($slice_id);
//		$slice->comment = Input::get('comment');
//		$slice->save();
//
//		return redirect()->route('in_progress_transcription_jobs');
//	}

	public function rate($audio_slice_id)
	{
		return view('audio_slice.rate');
	}

	// rate audio slice
	public function postRate(Request $request, $audio_slice_id)
	{
		$audio_slice = AudioSlice::findOrFail($audio_slice_id);
		if ($audio_slice->isRated()) { // no rating
			Log::error('audio slice is already rated');
			Abort(400, 'audio slice is already rated');
		}
		if (!$audio_slice->whereHas('audio.order.user', function($q) { // same editor that edits order
			$q->editor()->where('id', Auth::id());
		}));

		// on error exception generated and user is redirected back
		$this->validate($request, [
			'rating' => 'required|min:1|max:5'
		]);

		$audio_slice->rating = $request->rating;
		$audio_slice->editor_comment = $request->editor_comment;
		$audio_slice->save();

		// update global user rating
		UserRating::recalculateAll($audio_slice->transcriber_id);

		Email::transcriberAudioSliceRated($audio_slice);

		return redirect()->route('finished_editing_jobs');
	}

}
