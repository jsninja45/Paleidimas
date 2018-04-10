<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Validator;
use PayPal\Common\PayPalModel;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Api\CreditCard;
use PayPal\Api\FundingInstrument;

use App\Order;
use Illuminate\Support\Facades\Log;


// http://www.17educations.com/laravel/paypal-integration-in-laravel/

// there is laravel IPN in /vendor/laravel_ipn/index.php
// after transaction happens, paypal send transaction data to this file
// there is user email and name

class PaypalController extends Controller {

	private $_api_context;

	public function __construct()
	{
		// setup PayPal api context
		$paypal_conf = Config::get('paypal');
		$this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
		$this->_api_context->setConfig($paypal_conf['settings']);
	}

	public function pay(Request $request)
	{
		$order = Order::getUnpaidOrder();
		$order->setCoupon($request->get('coupon'));
		$total = $order->clientPrice($request->get('coupon'));

		// price 0 (when used coupon)
		if ($total == 0) {
			$order = Order::getUnpaidOrder();

			$order->pay(0); // checks amount and aborts php execution if not correct

			return redirect(route('upload_complete', $order->id));
		}


		if ($request->get('payment_type') === 'creditcard') {
			$card = new CreditCard();
			$card->setType(trim($request->get('creditcard_type'))) // visa, mastercard, discover, amex
				->setNumber(trim($request->get('creditcard_number')))
				->setExpireMonth((int)$request->get('expire_month'))
				->setExpireYear((int)$request->get('expire_year'))
				->setCvv2(trim($request->get('cvv')))
				->setFirstName(trim($request->get('first_name')))
				->setLastName(trim($request->get('last_name')));

//			$card->setType("visa") // visa, mastercard, discover, amex
//			->setNumber("4148529247832259")
//				->setExpireMonth("11")
//				->setExpireYear("2019")
//				->setCvv2("012")
//				->setFirstName("Joe")
//				->setLastName("Shopper");

			$fi = new FundingInstrument();
			$fi->setCreditCard($card);

			$payer = new Payer();
			$payer->setPaymentMethod("credit_card")
				->setFundingInstruments(array($fi));
		} else { // paypal
			$payer = new Payer();
			$payer->setPaymentMethod('paypal');
		}


		$item_1 = new Item();
		$item_1->setName('Transcription services') // item name
		->setCurrency('USD')
			->setQuantity(1)
			->setPrice($total); // unit price

		// add item to list
		$item_list = new ItemList();
		$item_list->setItems(array($item_1));

		$amount = new Amount();
		$amount->setCurrency('USD')
			->setTotal($total);

		$transaction = new Transaction();
		$transaction->setAmount($amount)
			->setItemList($item_list)
			->setDescription('Your transaction description');

		$redirect_urls = new RedirectUrls();
		$redirect_urls->setReturnUrl(URL::route('paypal.status'))
			->setCancelUrl(URL::route('paypal.status'));

		$payment = new Payment();
		$payment->setIntent('Sale')
			->setPayer($payer)
			->setRedirectUrls($redirect_urls)
			->setTransactions(array($transaction));

		try {
			$payment->create($this->_api_context);
		} catch (\PayPal\Exception\PPConnectionException $ex) {
			if (\Config::get('app.debug')) {
				echo "Exception: " . $ex->getMessage() . PHP_EOL;
				$err_data = json_decode($ex->getData(), true);
				exit;
			} else {
				die('Some error occurred');
			}
		} catch (\PayPal\Exception\PayPalConnectionException $ex) {

			Log::error($ex->getMessage(), (array)json_decode($ex->getData(), true));

			return redirect()->route('payment')->with(['error' => 'Payment error']);
		}

		// pay with credit card
		if ($request->get('payment_type') === 'creditcard') {
			if ($payment->getState() == 'approved') { // payment made
				$paid_amount = self::getAmountInUSD($payment);

				$order = Order::getUnpaidOrder();

				$order->pay($paid_amount); // checks amount and aborts php execution if not correct

				$transaction = $order->client_payment;
				if ($transaction) {
					$last_4_digits = substr(trim($request->get('creditcard_number')), -4);
					$transaction->payment_creditcard = $last_4_digits;
					$transaction->save();
				}

				return redirect(route('upload_complete', $order->id));
			}
		}


		foreach($payment->getLinks() as $link) {
			if($link->getRel() == 'approval_url') {
				$redirect_url = $link->getHref();
				break;
			}
		}

		// add payment ID to session
		Session::put('paypal_payment_id', $payment->getId());

		if(isset($redirect_url)) {
			// redirect to paypal
			return Redirect::away($redirect_url);
		}


		return Redirect::route('paypal_status_message')->with('error', 'Unknown error occurred');
	}


	public function callback()
	{
		// Get the payment ID before session clear
		$payment_id = Session::get('paypal_payment_id');

		// clear the session payment ID
		Session::forget('paypal_payment_id');

		if (Input::get('PayerID') == '' || Input::get('token') == '') {
			return Redirect::route('paypal_status_message')
				->with('error', 'Payment failed');
		}

		$payment = Payment::get($payment_id, $this->_api_context);

		// PaymentExecution object includes information necessary
		// to execute a PayPal account payment.
		// The payer_id is added to the request query parameters
		// when the user is redirected from paypal back to your site
		$execution = new PaymentExecution();
		$execution->setPayerId(Input::get('PayerID'));

		//Execute the payment
		$result = $payment->execute($execution, $this->_api_context);


		if ($result->getState() == 'approved') { // payment made
			$paid_amount = self::getAmountInUSD($result);

			$order = Order::getUnpaidOrder();

			$order->pay($paid_amount); // checks amount and aborts php execution if not correct

			$payment_method = 'paypal';
			if ($result->payer->payment_method === 'creditcard') {
				$payment_method = 'creditcard';
			}

			$client_payment = $order->client_payment;
			$client_payment->payment_type = $payment_method;
			$client_payment->payment_email = $result->payer->payer_info->email;
			$client_payment->payment_name = $result->payer->payer_info->first_name . ' ' . $result->payer->payer_info->last_name;
			$client_payment->save();

			return redirect(route('upload_complete', $order->id));

//			return Redirect::route('paypal_status_message')->with('success', 'Payment success');
		}

		return Redirect::route('payment')->with('error', 'Payment failed');
	}


	public function statusMessage()
	{
		return view('paypal.status_message');
	}

	private static function getAmountInUSD(PayPalModel $result) {
		$transactions = $result->getTransactions();
		$result = $transactions[0]->toArray();

		$total_amount = $result['amount']['total'];
		$currency = $result['amount']['currency'];
		if ($currency !== 'USD') {
			Log::error('Currency wasnt USD', ['currency' => $currency, 'amount' => $total_amount]);
			die('Error: wrong currency');
		}

		if (!$total_amount || $total_amount <= 0) {
			Log::error('No money amount specified', ['currency' => $currency, 'amount' => $total_amount]);
			die('Error: wrong money amount');
		}

		return $total_amount;
	}
}
