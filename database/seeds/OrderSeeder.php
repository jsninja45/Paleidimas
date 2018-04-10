<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Order;
use App\User;
use App\AudioSliceTranscription;

class OrderSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$file_list = [
			[
				'original_filename' => '1.mp3',
//				'client_price' => 12.34,
				'from' => 14,
				'till' => 56,
				'comment' => '',
			],
			[
				'original_filename' => '2.mp3',
//				'client_price' => 32,
				'comment' => '',
			],
			[
				'original_filename' => '2.mp3',
//				'client_price' => 32,
				'comment' => '',
			],
		];



		// order 1 (not taken)
		$order = Order::createOrder($file_list); // unpaid
		$order->client_id = 4;
		$order->save();

		// order 2 (transcriber)
		$order = Order::createOrder($file_list);
		$order->client_id = 4;
		$order->save();

		$order->pay($order->clientPrice());
		$order->save();

		$user = User::findOrFail(1);
		$slice_ids = $order->audios()->first()->slices()->lists('id');
		foreach ($slice_ids as $slice_id) {
			$user->takeAudioSlice($slice_id);

			$transcription = new AudioSliceTranscription();
			$transcription->user_id = $user->id;
			$transcription->audio_slice_id = $slice_id;
			$transcription->save();

			$user->finishAudioSliceJob($slice_id);
		}

		//$user = User::findOrFail(1);
		//$user->

		// order 3 (editor)

	}

}