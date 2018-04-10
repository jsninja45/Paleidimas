<?php namespace App\Http\Controllers;

use App\Audio;
use App\AudioSlice;
use App\AudioSliceLog;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Salary;
use App\Tat;
use App\Timestamping;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\DB;


class TranscriptionJobController extends Controller {

	public function available() {
		$user = Auth::user();
		$audio_slices = AudioSlice::availableForUser($user)->lateFirst()->get();
		$audio_slices->load('audio.order.textFormat', 'audio.order.timestamping', 'audio.order.language', 'transcription', 'audio.order.speaker');
		$total = count($audio_slices);

		return view('transcription_jobs.available', compact('audio_slices', 'user', 'total'));
	}

	public function in_progress()
	{
		$user = Auth::user();

		$audio_slices = $user->audioSlices()->inProgress()->get();
		$audio_slices->load('audio.order.textFormat', 'audio.order.timestamping', 'audio.order.language', 'transcription', 'audio.order.speaker');

		return view('transcription_jobs.in_progress', compact('audio_slices', 'user'));
	}

	public function finished()
	{
		$user = Auth::user();

		$audio_slices = $user->audioSlices()->finished()->get();
		$audio_slices->load('audio.order.textFormat', 'audio.order.timestamping', 'audio.order.language', 'transcription', 'audio.order.speaker');

		return view('transcription_jobs.finished', compact('audio_slices', 'user'));
	}

    public function stats($user_id)
	{
        $user = User::findOrFail($user_id);
		$user->load('ratings.language');
		$transactions = $user->transactions()->orderBy('worker_payment_till', 'desc')->get();

		$canceled_jobs = AudioSliceLog::where('user_id', $user->id)->canceled()->count();
		$missed_deadline = AudioSliceLog::where('user_id', $user->id)->missedDeadline()->count();


//		$not_paid_earnings = $user->notPaidEarnings();
		
//		$not_paid_payment = $this->notPaidPayment(Auth::user());

		$tats = Tat::all();
		$timestampings = Timestamping::find([1,2]);

        $earned_till_now_from = Salary::earnedTillNowFrom($user);
        $earnings_till_now = Salary::earnedTillNow($user);

        return view('transcription_jobs.stats', compact('user', 'transactions', 'ratings', 'canceled_jobs', 'missed_deadline',  'tats', 'timestampings', 'earnings_till_now', 'earned_till_now_from'));
	}







	// how much money worker collected till last Monday
//	private function notPaidPayment($user)
//	{
//		$last_payment = $user->transactions()->paid()->orderBy('worker_payment_till', 'desc')->first();
//		$till = date('Y-m-d', strtotime('last Monday')); // day after which payment will be generated
//
//		$transcriber_money = $this->notPaidForTranscriptions($user, $last_payment, $till);
//		$editor_money = $this->notPaidForEditing($user, $last_payment, $till);
//
//		$from = null;
//		$amount = 0;
//		if ($transcriber_money) {
//			$from = $transcriber_money['from'];
//			$amount = $transcriber_money['amount'];
//		}
//		if ($editor_money) {
//			if ($editor_money['from'] < $from) {
//				$from = $editor_money['from'];
//			}
//			$amount += $editor_money['amount'];
//		}
//
//		if ($amount == 0) {
//			return null;
//		}
//
//		return [
//			'from' => $from,
//			'till' => $till,
//			'amount' => $amount,
//		];
//	}

	private function notPaidForTranscriptions($user, $last_payment, $till)
	{
		$audio_slices =  $user->audioSlices();
		if ($last_payment) {
			$audio_slices = $audio_slices->where('finished_at', '>', (string)$last_payment->worker_payment_till);
		}
		$audio_slices = $audio_slices->orderBy('finished_at')->get();

		if ($audio_slices->isEmpty()) {
			return null;
		} else {
			$from = date('Y-m-d', strtotime($audio_slices[0]->finished_at));
			$amount = AudioSlice::where('finished_at', '>=', $from)->where('finished_at', '<', $till)->sum('transcriber_price');

			if ($from >= $till) {
				return null;
			}

			return [
				'from' => $from,
				'till' => $till,
				'amount' => $amount,
			];
		}
	}

	private function notPaidForEditing($user, $last_payment, $till)
	{
		$audios = $user->editorAudios();
		if ($last_payment) {
			$audios = $audios->where('finished_at', '>', (string)$last_payment->worker_payment_till);
		}
		$audios = $audios->orderBy('finished_at')->get();

		if ($audios->isEmpty()) {
			return null;
		} else {
			$from = date('Y-m-d', strtotime($audios[0]->finished_at));
			$amount = Audio::where('finished_at', '>=', $from)->where('finished_at', '<', $till)->sum('editor_price');

			if ($from >= $till) {
				return null;
			}

			return [
				'from' => $from,
				'till' => $till,
				'amount' => $amount,
			];
		}
	}
}
