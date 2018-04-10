<?php namespace App\Http\Controllers;

use App\Config;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Transaction;
use Illuminate\Http\Request;
use App\Order;
use App\User;
use App\Audio;
use App\AudioSlice;
use App\AudioSubtitle;
use App\Tat;
use App\Timestamping;
use App\Language;
use App\RatingDelay;
use Illuminate\Support\Facades\Input;

class AdminController extends Controller {

	public function orders(Request $request)
	{
//		$orders = Order::whereNotNull('paid_at')->orderBy('finished')->orderBy('created_at', 'desc')->paginate(100);
        $orders = Order::has('audios')->filter($request)->orderBy('created_at', 'desc')->paginate(100);
		$orders->load('coupon');

		return view('admin.orders', compact('orders'));
	}

	public function audios(Request $request)
	{
        $audios = Audio::filter($request)->orderByRaw('case when status = "finished" then 2 else 1 end')->lateFirst()->paginate(100);

		return view('admin.audios', compact('audios'));
	}

	public function audioSlices(Request $request)
	{
		// not finished first
		$slices = AudioSlice::filter($request)->orderByRaw('case when status = "finished" then 2 else 1 end')->paginate(50);

		return view('admin.audio_slices', compact('slices'));
	}


	public function audioSubtitles(Request $request)
	{
		$subtitles = AudioSubtitle::filter($request)->paginate(50);
		
		return view('admin.audio_subtitles', compact('subtitles'));
	}


	public function transcribers(Request $request)
	{
		$transcribers = User::notDeleted()->whereHas('roles', function($query) {
			$query->where('name', 'transcriber');
		})->urlFilter($request)->paginate(100);
		$transcribers->load('roles');

		foreach ($transcribers as &$transcriber) {
			$transcriber->precalculated_rating = round($transcriber->transcriptionRating(), 1);
		}

		unset($transcriber);

		$languages = Language::orderBy('name')->get();

        // stats
        $total_transcribers = User::notDeleted()->transcriber()->count();
        $transcribers_ids = User::notDeleted()->transcriber()->lists('id');
        $total_working = AudioSlice::whereIn('transcriber_id', $transcribers_ids)->where('status', 'in_progress')->distinct('transcriber_id')->count('transcriber_id');
        $total_finished = AudioSlice::whereIn('transcriber_id', $transcribers_ids)->where('status', 'finished')->distinct('transcriber_id')->count('transcriber_id');
        $total_rated = \App\UserRating::whereIn('user_id', $transcribers_ids)->distinct('user_id')->count('user_id');

		return view('admin.transcribers', compact('transcribers', 'languages', 'total_transcribers', 'total_working', 'total_finished', 'total_rated'));
	}

	public function editors(Request $request)
	{
		$editors = User::notDeleted()->whereHas('roles', function($query) {
			$query->where('name', 'editor');
		})->urlFilter($request)->paginate(100);
		$editors->load('roles');

		$languages = Language::orderBy('name')->get();

        // stats
        $total_editors = User::notDeleted()->editor()->count();
        $editors_ids = User::notDeleted()->editor()->lists('id');
        $total_working = Audio::whereIn('editor_id', $editors_ids)->where('status', 'in_editing')->distinct('editor_id')->count('editor_id');
        $total_finished = Audio::whereIn('editor_id', $editors_ids)->where('status', 'finished')->distinct('editor_id')->count('editor_id');
        $total_rated = \App\UserRating::whereIn('user_id', $editors_ids)->distinct('user_id')->count('user_id');

		return view('admin.editors', compact('editors', 'languages', 'total_editors', 'total_working', 'total_finished', 'total_rated'));
	}

	public function subtitlers(Request $request)
	{
		$subtitlers = User::notDeleted()->whereHas('roles', function($query) {
			$query->where('name', 'subtitler');
		})->urlFilter($request)->paginate(100);
		$subtitlers->load('roles');

		$languages = Language::orderBy('name')->get();

        // stats
        $total_subtitlers = User::notDeleted()->subtitlers()->count();
        $subtitlers_ids = User::notDeleted()->subtitlers()->lists('id');
        $total_working = Audio::whereIn('subtitler_id', $subtitlers_ids)->where('status', 'in_editing')->distinct('subtitler_id')->count('subtitler_id');
        $total_finished = Audio::whereIn('subtitler_id', $subtitlers_ids)->where('status', 'finished')->distinct('subtitler_id')->count('subtitler_id');
        $total_rated = \App\UserRating::whereIn('user_id', $subtitlers_ids)->distinct('user_id')->count('user_id');

		return view('admin.subtitlers', compact('subtitlers', 'languages', 'total_subtitlers', 'total_working', 'total_finished', 'total_rated'));
	}

	public function clients(Request $request)
	{
		$clients = User::urlFilter($request)->orderBy('created_at', 'desc')->paginate(100);
		foreach ($clients as &$client) {
			$client->spent = $client->spent();
		}
		unset($client);

		return view('admin.clients', compact('clients'));
	}

	public function coupons()
	{
		echo '<pre>'; print_r('here'); die(' ' . __FILE__ . ':' . __LINE__);

		return view('admin.coupons');
	}

	public function configuration()
	{
		$tats = Tat::get();
		$timestampings = Timestamping::get();
		$languages = Language::get();
		$rating_delays = RatingDelay::orderBy('rating_till')->get();
		$slice_no_rating_delay = Config::get('slice_no_rating_delay');

		$rating_delay_difference = $rating_delays[1]->rating_till - $rating_delays[0]->rating_till;

		return view('admin.configuration', compact('tats', 'timestampings', 'languages', 'rating_delays', 'slice_no_rating_delay', 'rating_delay_difference'));
	}

	public function projectStats()
	{
		$months = [];
		for ($i = 0; $i < 12; $i++) {
		    $from = date('Y-m-01 00:00:00', strtotime('-' . $i . ' month'));
		    $till = date('Y-m-t 23:59:59', strtotime('-' . $i . ' month'));

			$month = date('Y-m', strtotime('-' . $i . ' month'));
			$months[$month]['income'] = Transaction::where('created_at', '>=', $from)->where('created_at', '<=', $till)->where('amount', '>', 0)->sum('amount');
			$months[$month]['expenses'] = -Transaction::where('created_at', '>=', $from)->where('created_at', '<=', $till)->where('amount', '<', 0)->sum('amount');
			$months[$month]['profit'] = $months[$month]['income'] - $months[$month]['expenses'];

			if ($months[$month]['income'] == 0) {
				$months[$month]['profit_percents'] = 0;
			} else {
				$months[$month]['profit_percents'] = $months[$month]['profit'] / $months[$month]['income'] * 100;
			}
		}

		return view('admin.project_stats', compact('months'));
	}

	public function payWorker(Request $request)
	{
		$user_id = $request->get('user_id');
		$from = $request->get('from') . ' 00:00:00';
		$till = $request->get('till') . ' 23:59:59';
		$amount = $request->get('amount');

		Transaction::payToUser($user_id, $from, $till, $amount);

		return redirect()->back();
	}

	public function lateAudios()
	{
        $audios = Audio::late()->lateFirst()->paginate(50);

		return view('admin.late_audios', compact('audios'));
	}

	public function lateAudioSlices()
	{
		$audio_slices = AudioSlice::late()->orderBy('deadline_at', 'ASC')->paginate(50);
		$audio_slices->load('audio');

		return view('admin.late_audio_slices', compact('audio_slices'));
	}

	public function clientRatings()
	{
		$audios = Audio::whereNotNull('rating')->orderBy('rated_at', 'desc')->get();
		$audios->load('order.textFormat', 'order.timestamping', 'order.language');

		return view('admin.client_ratings', compact('audios'));
	}

	public function loginAs($user_id)
	{
		\Illuminate\Support\Facades\Auth::loginUsingId($user_id);

		return redirect('/');
	}

}
