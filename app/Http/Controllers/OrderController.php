<?php namespace App\Http\Controllers;

use App\Discount;
use App\Http\Requests;

use Illuminate\Http\Request;
use App\User;
use App\Order;

class OrderController extends Controller {

	public function index(Request $request, $user_id)
	{
		$orders = User::findOrFail($user_id)->orders()->whereNotNull('paid_at')->orderBy('created_at', 'desc')->paginate(10);
		$user = User::findOrFail($user_id);

		$paid_minutes = $user->paidMinutes();
		$discount = Discount::discount($paid_minutes);
		$next_discount = Discount::next_discount($paid_minutes);

		// get user's old orders (imported from drupal)
        $old_orders = $user->oldOrders()->orderBy('timestamp', 'desc')->get();

		return view('orders.index', compact('orders', 'user', 'discount', 'next_discount', 'paid_minutes', 'old_orders'));
	}

	public function show($user_id, $order_id)
	{
		$order = Order::with('client_payment')->findOrFail($order_id);
		$order->load('audios.transcriptions', 'user');

		return view('orders.show', compact('order'));
	}

	public function invoice($user_id, $order_id)
	{
		$order = \App\Order::paid()->findOrFail($order_id);

//		return view('pdf.pages.invoice', compact('order')); // view


		$html = view('pdf.pages.invoice', compact('order'))->render();

		return \PDF::load($html)->filename($order->number . '-invoice.pdf')->download(); // pdf


	}

	public function delete($order_id)
	{
		$order = Order::findOrFail($order_id);
		$order->delete();

		return redirect()->back();
	}

    public function oldFileDownload($user_id, $order_id)
    {
        $user = User::findOrFail($user_id);
        $order = $user->oldOrders()->where('jid', $order_id)->first();

        $file = $order->getTranscriptionPath();

        if (!$order OR !file_exists($file)) {
			abort(404);
        }

		return response()->download($file, $order->t_filename);
    }
}
