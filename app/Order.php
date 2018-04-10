<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Audio;
use App\AudioSlice;


class Order extends Model {

	public static function boot()
	{
		parent::boot();

		Order::deleting(function($order)
		{
			deleteAll($order->audios);
			deleteOne($order->client_payment);
		});
	}

	public function getDates()
	{
		return [
			'created_at',
			'updated_at',
			'paid_at',
		];
	}

	// ------------------------------------------------ relationships --------------------------------------------------
	public function user()
	{
		return $this->belongsTo('App\User', 'client_id');
	}

	public function audios()
	{
		return $this->hasMany('App\Audio');
	}

	public function tat()
	{
		return $this->belongsTo('App\Tat');
	}

	public function textFormat()
	{
		return $this->belongsTo('App\TextFormat');
	}

	public function timestamping()
	{
		return $this->belongsTo('App\Timestamping');
	}

	public function subtitle()
	{
		return $this->belongsTo('App\Subtitle');
	}

	public function speaker()
	{
		return $this->belongsTo('App\Speaker');
	}

	public function language() {
		return $this->belongsTo('App\Language');
	}

	public function client_payment() {
		return $this->hasOne('App\Transaction');
	}

	public function coupon()
	{
		return $this->belongsTo('App\Coupon');
	}

	// --------------------------------------------------- scopes ------------------------------------------------------
	public function scopeOnlyPaid($query) {
		$query->whereNotNull('paid_at');
	}

	public function scopeOnlyUnpaid($query) {
		$query->whereNull('paid_at');
	}

	public function scopePaid($query) {
		$query->whereNotNull('paid_at');
	}

	public function scopeUnpaid($query) {
		$query->whereNull('paid_at');
	}

	public function scopeFinished($q)
	{
		$q->where('status', 1);
	}

	// 24 - 25 hours ago
	public function scopeCreated24HoursAgo($query)
	{
		$query->where('orders.created_at', '>=', date('Y-m-d H:i:s', strtotime('-25 hour')))->where('orders.created_at', '<', date('Y-m-d H:i:s', strtotime('-24 hour')));
	}

	// 72 - 73 hours ago
	public function scopeCreated72HoursAgo($query)
	{
		$query->where('orders.created_at', '>=', date('Y-m-d H:i:s', strtotime('-73 hour')))->where('orders.created_at', '<', date('Y-m-d H:i:s', strtotime('-72 hour')));
	}

	// > 7 days
	public function scopeCreated7DaysAgo($query)
	{
		$query->where('orders.created_at', '<', date('Y-m-d H:i:s', strtotime('-7 day')));
	}

	public function scopeLate($q)
	{
		$q->whereNotNull('deadline_at')->where('finished', 0)->where('deadline_at', '<', date('Y-m-d H:i:s'));
	}

	public function scopeFilter($q, Request $request)
	{
		if ($request->has('order_id')) {
			if (is_numeric($request->order_id)) {
				$q->where('id', $request->order_id);
			} else {
				$q->where('id', str_replace('S2T', '', $request->order_id));
			}
		}
		if ($request->has('email')) {
			$q->whereHas('user', function($q) use ($request) {
				$q->where('email', trim($request->email));
			});
		}
		if ($request->has('date')) {
			$from = date('Y-m-d 00:00:00', strtotime($request->date));
			$till = date('Y-m-d 23:59:59', strtotime($request->date));
			$q->whereBetween('created_at', [$from, $till]);
		}
		if ($request->has('client_id')) {
			$q->where('client_id', $request->client_id);
		}
		if ($request->has('from_affiliate')) {
			$q->whereHas('user', function($q) {
                $q->where(function($q) {
                    $q->whereNotNull('affliate_abovealloffers')
                        ->orWhereNotNull('referrer');
                });
			});
		}
		if ($request->has('paid')) {
			$q->whereNotNull('paid_at');
		}
	}

	// ---------------------------------------------------- attributes -------------------------------------------------
	public function getNumberAttribute()
	{
		return 'S2T' . $this->id;
	}

	// ------------------------------------------------------ methods --------------------------------------------------
	public static function createOrder(array $file_list)
	{
		// order
		$order = new Order;
		$order->language_id = 2;
		$order->speaker_id = 2;
		$order->text_format_id = 1;
		$order->timestamping_id = 1;
		$order->tat_id = 2;
		$order->save();

		// audios
		$audios = [];
		foreach ($file_list as $file) {
			Model::unguard();
			$audio = new Audio();
			$audio->fill($file);
			$audio->save();
			Model::reguard();
			$audios[] = Audio::checkAndSaveAudio($audio);
		}
		$order->audios()->saveMany($audios);

//		foreach ($order->audios as $audio) {
//			$audio->deadline_at = date('Y-m-d H:i:s', time() + ($audio->tat->days * 3600 * 24));
//			$audio->save();
//		}

		return $order;
	}

	public function pay($amount) { // and create audio slices

		if ((float)$amount !== (float)$this->clientPrice()) {
			Log::error('Wrong payment amount');
			abort(401, 'Wrong payment amount');
		}

		if ($this->audios->count() == 0) {
			Log::error('Tried to pay for order where was no audios, order id' . $this->id);
			header('Location: ' . route('upload'));
			exit;
		}


		$this->paid_at = time();
		$this->client_price = $amount;
		$this->deadline_at = $this->deadline();
		$this->save();

		foreach ($this->audios as $audio) {
			$audio->status = 'in_transcription';
//			$audio->deadline_at = date('Y-m-d H:i:s', time() + ($audio->order->tat->days * 3600 * 24));
			$audio->client_price = $amount / ($this->minutes() * 60) * $audio->duration;
			$audio->save();
		}

		// create audio slices
		foreach ($this->audios as $audio) {
			$audio->slice();
		}

		if ($this->coupon) {
			CouponUsage::useCoupon($this->coupon, Auth::user());
		}

		Transaction::receiveForOrder($this->id, $this->client_id, $amount);

		Email::clientOrderPaid($this);
		Email::adminOrderPaid($this);
		Email::transcriberNewOrder($this);

		Affiliate::userPurchased($this);
	}

	public function changeStatusIfAllAudiosFinished()
	{
		if ($this->finished) {
			Log::warning('order is already finished');
			return false;
		}

		foreach ($this->audios as $audio) {
			if ($audio->status !== 'finished') {
				Log::warning('this audio is already finished');
				return false;
			}
		}

		$this->finished = '1';
		$this->save();

		Email::clientOrderFinish($this);

		return true;
	}

	// price for the client
	public function clientPrice($coupon_code = null, $external_minutes = null)
	{
		$subtotal = $this->subtotal($external_minutes);
		$discount = $this->discount($coupon_code, $external_minutes);

		if ($discount['type'] === 'percent') {
			$total = $subtotal * (100 - $discount['value']) / 100;
		} elseif ($discount['type'] === 'amount') {
			// user default (for minutes)
			$minutes_discount_percent = $this->minutesDiscountPercent($external_minutes);
			$total = $subtotal * (100 - $minutes_discount_percent) / 100;

			// coupon
			$total = $total - $discount['value'];
			if ($total < 0) {
				$total = 0;
			}
		} else {
			$total = $subtotal;
		}

		return round($total, 2); // rounding for correct price calculation when paying
	}

	public function setCoupon($coupon_code)
	{
		$coupon = Coupon::getCoupon($coupon_code);
		if ($coupon) {
			$this->coupon_id = $coupon->id;
		} else {
			$this->coupon_id = null;
		}

		return $this->save();
	}

	// for minutes or coupon discount
	public function discount($coupon_code = null, $external_minutes = null)
	{
		$coupon = Coupon::getCoupon($coupon_code);
		if ($coupon || $this->coupon_id) {
			$coupon = null;
			if ($coupon_code) {
				$coupon = $this->couponDiscount($coupon_code);
			} else if ($this->coupon_id) {
				$coupon = $this->coupon;
			}

			if ($coupon) {
				if ($coupon->type === 'percent') {
					return [
						'type' => $coupon->type,
						'value' => $coupon->value,
					];
				} elseif ($coupon->type === 'amount') {
					return [
						'type' => $coupon->type,
						'value' => $coupon->value,
					];
				}
			}
		} else {
			$minutes_discount_percent = $this->minutesDiscountPercent($external_minutes);

			return [ // for minutes
				'type' => 'percent',
				'value' => $minutes_discount_percent,
			];
		}
	}

	private function couponDiscount($coupon_code)
	{
		// coupon discount
		$coupon = null;
		if ($coupon_code) {
			$coupon = Coupon::getCoupon($coupon_code);
		}
		if (!$coupon) { // maybe order already has coupon set
			$coupon = $this->coupon;
		}


		return $coupon;
	}

	// discount for minutes
	private function minutesDiscountPercent($external_minutes = null)
	{
		// minutes discount
		$minutes_discount_percent = 0;
		$total_minutes = 0;
		if ($external_minutes !== null) {
			$total_minutes = $external_minutes;
		} else {
			if ($this->user) {
				$total_minutes += $this->user->paidMinutes();
			}

			$total_minutes += $this->minutes();
		}

		$minutes_discount_percent = Discount::discount($total_minutes)->percent;

		return $minutes_discount_percent;
	}

	// without discount
	public function subtotal($external_minutes = null) // minutes - when you want to use external minutes
	{
		$order = $this->getUnpaidOrder();

		if ($external_minutes === null) {
			$duration = $order->totalAudioDuration();
			$minutes = $duration / 60;
		} else {
			$minutes = $external_minutes;
		}


		$price_per_minute =
			$this->textFormat->client_price_per_minute
			+ $this->timestamping->client_price_per_minute
			+ $this->speaker->client_price_per_minute
			+ $this->tat->client_price_per_minute
			+ $this->subtitle->client_price_per_minute
		;

		$price_without_discount = round($minutes * $price_per_minute, 2);

		return $price_without_discount;
	}

    // get user unpaid order (user can be logged in or not)
    public static function getUnpaidOrder() {
        if (Auth::check()) {
            $order = Auth::user()->orders()->onlyUnpaid()->first();
        } else {
            $order = Order::whereNull('client_id')->where('id', Session::get('order_id'))->first();
        }

        if (!$order) {
            $order = new Order();
            if (Auth::check()) {
                $order->client_id = Auth::id();
            }
            $order->tat_id = Tat::where('slug', 'days_14')->first()->id;
            $order->text_format_id = TextFormat::first()->id;
            $order->speaker_id = Speaker::first()->id;
            $order->timestamping_id = Timestamping::first()->id;
            $order->subtitle_id = Subtitle::first()->id;
            $order->language_id = Language::where('name', 'English')->first()->id;
            $order->save();
        }

        if (!Auth::check()) {
            Session::put('order_id', $order->id);
        }

        return $order;
    }

	// in seconds
	public function totalAudioDuration()
	{
		return $this->audios()->sum('till') - $this->audios()->sum('from');

		//return $this->audios()->sum('duration');
	}

	// how much this order has minutes
	public function minutes()
	{
		return ($this->audios()->sum('till') - $this->audios()->sum('from')) / 60;
	}

	// is in progress?
//	public function inProgress()
//	{
//		$in_progress = Order::where('id', $this->id)->whereHas('audios', function($query) {
//			$query->whereHas('slices', function($query) {
//				$query->whereNotNull('transcriber_id');
//			});
//		})->exists();
//
//		return $in_progress;
//	}

	public function link($param = null)
	{
		if (!$param) {
			return route('order', ['user_id' => $this->user->id, 'order_id' => $this->id]);
		} elseif ($param === 'invoice') {
			return route('order_invoice', ['user_id' => $this->user->id, 'order_id' => $this->id]);
		} elseif ($param === 'delete') {
			return route('delete_order', ['order_id' => $this->id]);
		}

		die('wrong data');
	}

	public function deadline()
	{
		$days = $this->tat->days;

		if ($this->paid_at) {
			$timestamp = strtotime($this->paid_at . ' +' . $days . ' day');
			$deadline_at = date('Y-m-d H:i:s', $timestamp);
		} else {
			$deadline_at = null;
		}

		return $deadline_at;
	}

	public function doesOrderNeedSubtitles()
	{
		if ($this->subtitle->slug !== 'not_required') {
			return true;
		} else {
			return false;
		}
	}

	public function isFinished()
	{
		return (bool)$this->finished;
	}

}
