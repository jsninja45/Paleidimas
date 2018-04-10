<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Transaction;
use Illuminate\Http\Request;
use App\User;

class TransactionController extends Controller {

	public function workerPaymentDetails(Request $request, $user_id, $transaction_id)
	{
		$user = User::findOrFail($user_id);
		$transaction = Transaction::findOrFail($transaction_id);

		$from = $transaction->worker_payment_from;
		$till = $transaction->worker_payment_till;

		$audios = $user->editorAudios()->whereBetween('finished_at', [$from, $till])->get();
		$audio_slices = $user->audioSlices()->whereBetween('finished_at', [$from, $till])->get();
		$subtitler_audios = $user->subtitlerAudios()->where('subtitler_price', '>', 0)->where('finished_at', '>', $from)->where('finished_at', '<=', $till)->get();
		$bonuses = $user->bonuses()->where('created_at', '>', $from)->where('created_at', '<=', $till)->get();

//		$user = User::findOrFail($user_id);
//		$audios = $user->audios()->finished();
//		$audio_slices = $user->audioSlices()->finished();
//
//		if ($request->has('from')) {
//			$from = date('Y-m-d 00:00:00', strtotime($request->get('from')));
//
//			$audios->where('finished_at', '>=', $from);
//			$audio_slices->where('finished_at', '>=', $from);
//		}
//		if ($request->has('till')) {
//			$till = date('Y-m-d 23:59:59', strtotime($request->get('till')));
//
//			$audios->where('finished_at', '<=', $till);
//			$audio_slices->where('finished_at', '<=', $till);
//		}
//
//		$audios = $audios->get();
//		$audio_slices = $audio_slices->get();

		return view('transactions.payments', compact('audios', 'audio_slices', 'subtitler_audios', 'bonuses'));
	}

}
