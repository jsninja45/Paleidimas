<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Review;

class ReviewSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Review::create([
			'name' => 'Rachel',
			'rating' => 5,
			'content' => 'My field of interest is photography and there are many specific terms used in this area, so I was worried I wouldn’t find a transcription service that could meet my requests. Not only SpeechToTextService did a great job and got all the terms right, but they had the work done before the deadline I had set for them. I am very happy with their services.',
		]);

		Review::create([
			'name' => 'Chen',
			'rating' => 5,
			'content' => 'Finding a good transcription service has always been very difficult for me, because my accent is very strong and most transcribers don’t understand it. But things changed when I found SpeechToTextService, they provided 100% accuracy. I couldn’t believe it! So I went on Live Support and asked them how they managed to do what others before them couldn’t. The answer was shocking simple: they have a native Chinese speaker in their team for whom it was easy to understand what I was saying. Great work, guys, very inspiring!',
		]);

		Review::create([
			'name' => 'Carolyn',
			'rating' => 5,
			'content' => 'I was very skeptical about using a transcription service, especially one that’s new on the market, but then I’ve noticed that SpeechToTextService offers a 3 minute free trial, so I said what the heck? Let’s give it a shot as long as it’s free. In 30 minutes I got back a flawless transcription of my 3 minutes, but that still didn’t convince me of their high-quality work. Anyway, I uploaded my file and got it back in less than 12 hours, it was exactly as advertised: 100% accuracy. Now I am definitely convinced and I am sticking with them, especially since I got my first discount for my uploaded files yesterday!',
		]);
	}


}