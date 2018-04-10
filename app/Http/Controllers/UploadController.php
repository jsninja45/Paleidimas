<?php namespace App\Http\Controllers;

use App\Affiliate;
use App\Discount;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Subtitle;
use App\Upload;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Order;
use App\Audio;
use App\FAQ;
use App\Login;
use App\Calculator;
use App\TextFormat;
use App\Speaker;
use App\Language;
use App\Tat;
use App\Timestamping;

class UploadController extends Controller {


	public function upload()
	{
		$order = Order::getUnpaidOrder();
		$order->load('audios');

		$faqs = FAQ::orderBy('created_at', 'desc')->take(3)->get();

		$my_order = self::dataForMyOrderPanel($order);

		return view('upload.upload', compact('order', 'faqs', 'my_order') + Calculator::viewData());
	}

	public function takeFile(Request $request)
	{
		$audio = Audio::takeFile($request->get('file'));
		if (!$audio) {
			abort(400, 'Upload files not found or bad file format');
		}

		return $audio;
	}

	public function deleteAudio(Request $request)
	{
		$audio_id = $request->get('audio_id');
		$audio = Audio::findOrFail($audio_id);

		// validation (no audio owner)
		if (!$audio->order->user || ($audio->order->user->id == Auth::id())) {
			File::delete(storage_path() . '/app/audios/' . $audio->id);
			$audio->delete();
		} else {
			Log::error('This audio has user');
			abort(401, 'This audio has user');
		}
	}

	public function register()
	{
		Login::afterLoginRedirectToPayment(); // !!!

		$order = Order::getUnpaidOrder();
		$order->load('audios');

		if (!$order->audios()->exists()) {
			return redirect()->route('upload')->with('error', 'No files uploaded');
		}

		if (Auth::check()) {
			return redirect()->route('payment');
		}

		return view('upload.register', compact('order'));
	}

	public function postRegister(Request $request)
	{
		$order = Order::getUnpaidOrder();

		$validator = Validator::make($request->only('email', 'password'), [
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required',
		]);
		if ($validator->fails())
		{
			return redirect()->back()->withErrors($validator->errors());
		} else {
			$user = new User();
			$user->email = $request->email;
			$user->password = $request->password;
			$user->save();
		}

		Auth::attempt($request->only('email', 'password'));


		// assign order to user
		Upload::assignOrderToUser($order);

		Login::afterLogin(Auth::user());

		return redirect()->back();
	}

	public function postLogin(Request $request)
	{
		$login = Login::loginUsingRequest($request);
		if ($login->errors()) {
			// error
			return $login->redirect(); // show error
		} else {
			// success
			return redirect()->route('payment');
		}
	}

	public function payment()
	{
		$order = Order::getUnpaidOrder();
		$order->load('audios', 'coupon');

		if ($order->audios->count() == 0) {
			return redirect()->route('upload');
		}

		$user = Auth::user();

		$subtotal = $order->subtotal();
		$discount = $order->discount();
		$price = $order->clientPrice();
		$you_save = $subtotal - $price;

		return view('upload.payment', compact('order', 'user', 'discount', 'subtotal', 'you_save'));
	}

	public function completed($order_id)
	{
		$orders = Order::with('client_payment')->where('id', $order_id)->get();

		return view('upload.complete', compact('orders'));
	}

	public function updateUnpaidOrder(Request $request) // with new user selections
	{
		$text_format = TextFormat::where('slug', $request->get('text_format'))->firstOrFail();
		$timestamping = Timestamping::where('slug', $request->get('timestamping'))->firstOrFail();
		$subtitle = Subtitle::where('slug', $request->get('subtitle'))->firstOrFail();
		$speakers = Speaker::where('slug', $request->get('speakers'))->firstOrFail();
		$tat = Tat::where('slug', $request->get('tat'))->firstOrFail();
		$language = Language::findOrFail($request->language);

		$order = Order::getUnpaidOrder();
		$order->text_format_id = $text_format->id;
		$order->timestamping_id = $timestamping->id;
		$order->subtitle_id = $subtitle->id;
		$order->speaker_id = $speakers->id;
		$order->tat_id = $tat->id;
		$order->language_id = $language->id;
		$order->save();

		$minutes = $order->minutes();
		$total = $order->clientPrice();
		$subtotal = $order->subtotal();
		$discount = $order->discount()['value'];
		$you_save = $subtotal - $total;

		$result = [
			'total' => $total,
			'subtotal' => $subtotal,
			'discount' => $discount,
			'you_save' => $you_save,
			'minutes' => $minutes,
		];

		return $result;
	}

	public function updateUnpaidOrder2(Request $request) // with new user selections
	{
		$text_format = TextFormat::where('slug', $request->get('text_format'))->firstOrFail();
		$timestamping = Timestamping::where('slug', $request->get('timestamping'))->firstOrFail();
		$subtitle = Subtitle::where('slug', $request->get('subtitle'))->firstOrFail();
		$speakers = Speaker::where('slug', $request->get('speakers'))->firstOrFail();
		$tat = Tat::where('slug', $request->get('tat'))->firstOrFail();

		$order = Order::getUnpaidOrder();
		$order->text_format_id = $text_format->id;
		$order->timestamping_id = $timestamping->id;
		$order->subtitle_id = $subtitle->id;
		$order->speaker_id = $speakers->id;
		$order->tat_id = $tat->id;
		$order->save();

		$external_minutes = $request->external_minutes;
		$minutes = $external_minutes;

		$total = $order->clientPrice(null, $external_minutes);
		$subtotal = $order->subtotal($external_minutes);
		$discount_minutes = $external_minutes;
		if (Auth::user()) {
			$discount_minutes = Auth::user()->paidMinutes() + $external_minutes;
		}
		$discount = $order->discount(null, $discount_minutes)['value'];
		$you_save = $subtotal - $total;



		$result = [
			'total' => $total,
			'subtotal' => $subtotal,
			'discount' => $discount,
			'you_save' => $you_save,
			'minutes' => $minutes,
			'discount_minutes' => $discount_minutes,
			'external_minutes' => $external_minutes,
		];
		$next_discount = Discount::next_discount($discount_minutes);
		if ($next_discount) {
			$result['next_discount']['minutes'] = $next_discount->minutes;
			$result['next_discount']['percent'] = $next_discount->percent;
		}

		return $result;
	}

	public function updateComment(Request $request)
	{
		$audio_id = $request->get('audio_id');
		$comment = trim($request->get('comment'));

		$order = Order::getUnpaidOrder();
		$audio_belongs_to_user = $order->audios()->where('id', $audio_id)->exists();
		if (!$audio_belongs_to_user) {
			abort('401', 'Audio doesnt belong to user');
		}

		$audio = Audio::findOrFail($audio_id);
		$audio->comment = $comment;
		$audio->save();
	}

	public function updateDuration(Request $request)
	{
		$audio_id = $request->get('audio_id');
		$from = $request->get('from');
		$till = $request->get('till');

		$order = Order::getUnpaidOrder();
		$audio_belongs_to_user = $order->audios()->where('id', $audio_id)->exists();
		if (!$audio_belongs_to_user) {
			abort('401', 'Audio doesnt belong to user');
		}

		$decoded_from = decodeHumanTime($from);
		$decoded_till = decodeHumanTime($till);

		if ($decoded_from === false || $decoded_till === false) {
			// error
			return Audio::findOrFail($audio_id);
		}

		$audio = Audio::findOrFail($audio_id);

		if ($decoded_from < 0 || $decoded_till > $audio->original_duration || $decoded_from >= $decoded_till) {
			// error
			return Audio::findOrFail($audio_id);
		}

		$audio->from = $decoded_from;
		$audio->till = $decoded_till;
		$audio->save();

		return Audio::findOrFail($audio->id);
	}

	public function addLinks(Request $request)
	{
		$links = explode("\n", trim($request->get('links')));

		$collection = new Collection();
		foreach ($links as $link) {
			$parsed_link = parse_url($link);
			if (isset($parsed_link['host'])) {
				$host = $parsed_link['host'];
			} else {
				continue;
			}

			$audio = null;
			if ($host === 'www.youtube.com' || $host === 'youtu.be') {
				$audio = youtubeAudio($link);
			} elseif ($host === 'vimeo.com') {
				$audio = vimeoAudio($link);
			} else {
				continue;
			}

			if (!$audio) {
				continue;
			}

			$audio = Audio::checkAndSaveAudio($audio);
			$collection->push($audio);
		}

		return $collection;
	}

	private static function dataForMyOrderPanel($order)
	{
		$subtotal = $order->subtotal();
		$discount = $order->discount();
		$price = $order->clientPrice();
		$you_save = $subtotal - $price;

		return compact('subtotal', 'discount', 'price', 'you_save');
	}

}
