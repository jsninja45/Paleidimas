<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

/*
Credit Card #: 5105105105105100
Expiry: 05/2015
CVV: 123
 */

class BraintreeController extends Controller {

	// send data to braintree
	public function send(Request $request)
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

		$inputs = $request->all();

		// generate form with data and automatically submit
		return view('braintree.send', compact('request', 'total', 'inputs'));
	}

	// check if user payed
	public function callback(Request $request)
	{
		$order = Order::getUnpaidOrder();
		$total = $order->clientPrice();


		if (!$request->has('id')) {
			return redirect()->route('payment')->with(['error' => 'Payment error (no id)']);
		}

		$partial_result = \Braintree_TransparentRedirect::confirm($_SERVER['QUERY_STRING']);

		if (!$partial_result) {
			return redirect()->route('payment')->with(['error' => 'Payment error']);
		}
		if ($partial_result->success == false) {
			return redirect()->route('payment')->with(['error' => $partial_result->message]);
		}

		$result = \Braintree_Transaction::submitForSettlement($partial_result->transaction->id);

		if (!$result) {
			return redirect()->route('payment')->with(['error' => 'Payment error (can\'t submit for settlement)']);
		}

		if ($result->success == false || $result->transaction->status !== 'submitted_for_settlement') {
			return redirect()->route('payment')->with(['error' => $result->message]);
		}

		$paid_amount = $result->transaction->amount;
		if ($paid_amount != $total) {
			return redirect()->route('payment')->with(['error' => 'Payment error (amount)']);
		}

		// success
		$order = Order::getUnpaidOrder();
		$order->pay($paid_amount);
		
		$client_payment = $order->client_payment;
		if ($client_payment) {
			$last_4_digits = substr(trim($result->transaction->creditCardDetails->maskedNumber), -4);
			$client_payment->payment_creditcard = $last_4_digits;
			$client_payment->payment_type = 'creditcard';
			$client_payment->payment_name = $result->transaction->customerDetails->firstName . ' ' . $result->transaction->customerDetails->lastName;
			$client_payment->save();
		}

		return redirect(route('upload_complete', $order->id));
	}

}
