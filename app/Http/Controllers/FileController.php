<?php namespace App\Http\Controllers;

use App\AudioSliceTranscription;
use App\AudioSubtitle;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\AudioSlice;
use App\AudioTranscription;
use App\Audio;

class FileController extends Controller {

	private $audio_folder;
	private $audio_slice_folder;

	public function __construct()
	{
		$this->audio_folder = storage_path('app/audio_transcriptions');
		$this->audio_slice_folder = storage_path('app/audio_slice_transcriptions');
		$this->audio_subtitle_folder = storage_path('app/audio_subtitles');
	}

	public function audioSliceTranscriptionUploadForm($audio_slice_id)
	{
		return view('files.audio_slice_transcription_upload', compact('audio_slice_id'));
	}

	/**
	 * Attaches file to audio or audioPart
	 *
	 * @param $audio_or_audioPart 	which one to use
	 * @param $job_id 				audio or audio part id
	 */
	public function audioSliceTranscriptionUploadSave($audio_slice_id)
	{
		if (!Request::hasFile('file') || !Request::file('file')->isValid()) {
			return redirect()->back();
		}

		AudioSliceTranscription::where('audio_slice_id', $audio_slice_id)->delete(); // delete all

		$input_file = Request::file('file');

		$file = new AudioSliceTranscription;
		$file->audio_slice_id = $audio_slice_id;
		$file->user_id = Auth::id();
		$file->filename = $input_file->getClientOriginalName();
		$file->save();

		$input_file->move($this->audio_slice_folder, $file->id);

		if (Auth::user()->hasRole('admin')) {
			return redirect()->route('admin_audio_slices');
		}

		return redirect()->route('in_progress_transcription_jobs');
	}

	public function audioTranscriptionUploadForm($audio_id)
	{
		return view('files.audio_transcription_upload', compact('audio_id'));
	}

	public function audioTranscriptionUploadSave($audio_id)
	{
		if (!Request::hasFile('file') || !Request::file('file')->isValid()) {
			return redirect()->back();
		}

		$audio = Audio::findOrFail($audio_id);

		$input_file = Request::file('file');

		$file = new AudioTranscription;
		$file->audio_id = $audio_id;
		$file->user_id = Auth::id();
		$file->filename = $input_file->getClientOriginalName();
		$file->save();

		$input_file->move($this->audio_folder, $file->id);

		if (Auth::user()->hasRole('admin')) {
			$url = route('admin_audios') . '?order_id=' . $audio->order->id;
			return redirect($url);
		}

		return redirect()->route('in_progress_editing_jobs');
	}

	public function audioSubtitleUploadForm($audio_id)
	{
		return view('files.audio_subtitles_upload', compact('audio_id'));
	}

	public function audioSubtitleUploadSave($audio_id)
	{
		if (!Request::hasFile('file') || !Request::file('file')->isValid()) {
			return redirect()->back();
		}

		$audio = Audio::findOrFail($audio_id);

		$input_file = Request::file('file');

		$file = new AudioSubtitle();
		$file->audio_id = $audio_id;
		$file->user_id = Auth::id();
		$file->filename = $input_file->getClientOriginalName();
		$file->save();

		$input_file->move($this->audio_subtitle_folder, $file->id);

		if (Auth::user()->hasRole('admin')) {
			$url = route('admin_audios') . '?order_id=' . $audio->order->id;
			return redirect($url);
		}

		return redirect()->route('subtitling_jobs.in_progress');
	}
}
