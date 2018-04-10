<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'facebook' => [
		'client_id' => env('FACEBOOK_ID'),
		'client_secret' => env('FACEBOOK_SECRET'),
		'redirect' => 'https://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . '/' . 'social-login/facebook/handle',
	],

	'google' => [
		'client_id' => env('GOOGLE_ID'),
		'client_secret' => env('GOOGLE_SECRET'),
		'redirect' => 'https://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . '/' . 'social-login/google/handle',
	],

	'twitter' => [
		'client_id' => env('TWITTER_ID'),
		'client_secret' => env('TWITTER_SECRET'),
		'redirect' => 'https://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . '/' . 'social-login/twitter/handle',
	],

	'mailgun' => [
		'domain' => 'sandboxaf20571d43424670b6945ef41a929833.mailgun.org',
		'secret' => 'key-553bfd57734e1a7edab2de75eb1f526b',
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'App\User',
		'key' => '',
		'secret' => '',
	],

];
