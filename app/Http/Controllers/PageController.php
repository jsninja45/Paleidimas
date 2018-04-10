<?php namespace App\Http\Controllers;

use App\Affiliate;
use App\Audio;
use App\AudioTranscription;
use App\Discount;
use App\Language;
use App\Order;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Timestamping;
use App\TextFormat;
use App\Article;
use App\User;
use App\Speaker;
use App\Tat;
use App\FAQ;
use App\Calculator;

class PageController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//$this->middleware('auth');
	}

	public function index(Request $request)
	{
		Affiliate::setCookie($request);

		$articles = Article::orderBy('created_at', 'desc')->limit(4)->get();
		$reviews = Review::orderBy('created_at', 'desc')->take(3)->get();
		$avg_review_rating = Review::avg('rating');
		$total_reviews = Review::count();
		$user_count = User::whereHas('orders', function($q) {
			$q->paid();
		})->count();
		$transcription_count = Audio::finished()->count();
		$hour_count = round((Audio::finished()->sum('till') - Audio::finished()->sum('from')) / 3600);

		$tats = Tat::orderBy('days', 'desc')->get();
		$tat_array = [];
		foreach ($tats as $tat) {
			$tat_array[$tat->days] = $tat;
		}

        $speaker_price = Speaker::orderBy('client_price_per_minute', 'desc')->firstOrFail()->client_price_per_minute;


		return view('home', compact('articles', 'tat_array', 'speaker_price', 'reviews', 'avg_review_rating', 'total_reviews', 'user_count', 'transcription_count', 'hour_count') + Calculator::viewData());
	}

	public function faq()
	{
		$faqs = FAQ::orderBy('question')->get();

		return view('pages.faq', compact('faqs'));
	}

	public function samples()
	{
		return view('pages.samples');
	}

	public function pricing()
	{
		$tats = Tat::orderBy('days', 'desc')->get();
		$tat_array = [];
		foreach ($tats as $tat) {
			$tat_array[$tat->days] = $tat;
		}

		$speaker_price = Speaker::orderBy('client_price_per_minute', 'desc')->firstOrFail()->client_price_per_minute;

		return view('pages.pricing', compact('tat_array', 'speaker_price') + Calculator::viewData());
	}

	public function calculatorJS()
	{
		return view('components.calculator.partial.js.calculatorjs', Calculator::viewData());
	}

	public function freeTrial()
	{
		return view('pages.free_trial');
	}

	public function affiliate()
	{
		return view('pages.affiliate');
	}

	public function privacy()
	{
		return view('pages.privacy');
	}

	public function costEstimate()
	{
		return view('pages.cost_estimate', Calculator::viewData());
	}

}
