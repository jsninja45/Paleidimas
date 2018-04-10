<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Language;

class LanguageSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$languages = [
			'English',
			'French',
			'Spanish',
			'Italian',
			'Lithuanian',
			'Romanian',
			'Hindi',
			'Filipino',
			'Russian',
			'Polish',
			'Shona',
            'Portuguese',
            'Dutch',
//            'Tagalog',
            'German',
            'Turkish',
            'Macedonian',
            'Serbian',
            'Croatian',
            'Bosnian',
            'Arabic',
            'Chinese',
            'Japanese',
            'Greek',
            'Xhosa',
		];


		foreach ($languages as $language) {
			Language::create([
				'name' => $language,
			]);
		}


	}

}