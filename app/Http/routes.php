<?php

/*
TEST SQL QUERIES

Event::listen('illuminate.query', function($sql)
{
    echo $sql, '<br />';
});

-- or --

\Illuminate\Support\Facades\DB::enableQueryLog();
echo '<pre>'; print_r(\Illuminate\Support\Facades\DB::getQueryLog()); die(' ' . __FILE__ . ':' . __LINE__);

*/

// php artisan drop - drop all tables

// bootstrap theme https://bootswatch.com/cerulean/

// all audio durations are in SECONDS
// all audio prices are in DOLLARS PER MINUTE


Route::get('auth/check-credentials', ['uses' => 'Auth\AuthController@checkCredentials', 'as' => 'auth.checkCredentials']);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',

	//'admin' => 'Admin\AdminPageController',
]);


// pages
Route::get('/laravel-cron', 'CronController@index');
Route::get('/', ['uses' => 'PageController@index', 'as' => 'homepage']);
Route::get('/transcription-services', ['uses' => 'ServiceController@index', 'as' => 'services']);
Route::get('/transcription-services/{slug}', ['uses' => 'ServiceController@show', 'as' => 'service']);
Route::get('/faq', ['uses' => 'PageController@faq', 'as' => 'faq']);
Route::get('/about', ['uses' => 'ContactController@contacts', 'as' => 'contacts']);
Route::post('/about', 'ContactController@sendEmail');
Route::get('/blog', ['uses' => 'ArticleController@index', 'as' => 'blog']);
Route::get('/blog/{slug}', ['uses' => 'ArticleController@show', 'as' => 'post']);
Route::get('/feedback', ['uses' => 'ReviewController@index', 'as' => 'reviews']);
Route::post('/feedback', 'ReviewController@store');
Route::get('/our-transcription-samples', ['uses' => 'PageController@samples', 'as' => 'samples']);
Route::get('/pricing', ['uses' => 'PageController@pricing', 'as' => 'pricing']);
Route::get('/free-trial', ['uses' => 'PageController@freeTrial', 'as' => 'free_trial']);
Route::get('affiliate', ['uses' => 'PageController@affiliate', 'as' => 'affiliate']);
Route::get('privacy', ['uses' => 'PageController@privacy', 'as' => 'privacy']);
Route::get('audio-transcription-cost-estimate', ['uses' => 'PageController@costEstimate', 'as' => 'cost_estimate']);


Route::get('add-files', ['uses' => 'UploadController@upload', 'as' => 'upload']);
Route::post('upload/take-file', 'UploadController@takeFile');
Route::post('upload/add-links', 'UploadController@addLinks');
Route::post('upload/delete-audio/', 'UploadController@deleteAudio'); // validation inside
Route::get('upload/register', ['uses' => 'UploadController@register', 'as' => 'upload_register']);
Route::post('upload/register', 'UploadController@postRegister');
Route::post('upload/login', ['uses' => 'UploadController@postLogin', 'as' => 'upload_login']);
Route::post('upload/update-unpaid-order', 'UploadController@updateUnpaidOrder');
Route::post('upload/update-unpaid-order2', 'UploadController@updateUnpaidOrder2');
Route::post('upload/update-comment', 'UploadController@updateComment'); // validation inside
Route::post('upload/update-duration', 'UploadController@updateDuration'); // validation inside



Route::get('partial_transcriptions/{id}/download', ['middleware' => 'user.download_partial_transcription', 'uses' => 'AudioSliceTranscriptionController@show', 'as' => 'partial_transcription_download']);
Route::get('transcriptions/{transcription_id}/download', ['middleware' => 'user.download_transcription', 'uses' => 'AudioTranscriptionController@show', 'as' => 'transcription_download']);
Route::get('subtitles/{subtitle_id}/download', ['middleware' => 'user.download_subtitle', 'uses' => 'AudioSubtitleController@show', 'as' => 'subtitles.download']);

Route::get('/generated/calculator.js', 'PageController@calculatorJS');

Route::get('payment', ['uses' => 'UploadController@payment', 'as' => 'payment']);

// paypal
Route::post('paypal', array('as' => 'paypal', 'uses' => 'PaypalController@pay')); // pay with paypal
Route::get('paypal/status', array('as' => 'paypal.status', 'uses' => 'PaypalController@callback')); // after payment paypal redirects here
Route::get('paypal/status-message', ['uses' => 'PaypalController@statusMessage', 'as' => 'paypal_status_message']);
Route::get('paypal/ipn', 'PaypalController@ipn');

// braintree
Route::post('braintree/send', ['uses' => 'BraintreeController@send', 'as' => 'braintree_send']); // pay with braintree
Route::get('braintree/callback', ['uses' => 'BraintreeController@callback', 'as' => 'braintree_callback']); // pay with braintree

// social login
Route::get('social-login/{provider}', ['uses' => 'SocialLoginController@redirectToProvider', 'as' => 'social_login']);
Route::get('social-login/{provider}/handle', ['uses' => 'SocialLoginController@handleProviderCallback', 'as' => 'handle_social_login']);

Route::get('language/{id}/image', ['uses' => 'LanguageController@image', 'as' => 'languages.image']);

// AUTH
$router->group(['middleware' => 'auth'], function() {




	Route::get('admin', 'Admin\AdminPageController@index');

	Route::get('user/{id}', ['uses' => 'UserController@show', 'as' => 'profile']);
	Route::get('user/{id}/edit', ['uses' => 'UserController@edit', 'as' => 'edit_profile']);
	Route::post('user/{id}', 'UserController@update');






	//Route::get('messages/create/{recipient_id}', ['uses' => 'MessageController@create', 'as' => 'create_message']);
	Route::post('messages/create/{recipient_id}', ['uses' => 'MessageController@store', 'as' => 'store_message']);
	Route::get('messages/{with_user_id}', array('uses' => 'MessageController@showChatWith', 'as' => 'messages_with'));

	Route::post('coupon/check', 'CouponController@check');

});

// user id in url
$router->group(['middleware' => ['auth', 'user.id_in_url']], function() {
	Route::get('user/{user_id}/settings', ['uses' => 'UserController@settings', 'as' => 'user_settings']);
	Route::get('user/{user_id}/set-newsletter', 'UserController@setNewsletter');
	Route::get('user/{user_id}/email-settings', 'UserController@emailSettings');
	Route::post('user/{user_id}/delete-account', 'UserController@deleteAccount');
	Route::post('user/{user_id}/change-password', 'UserController@changePassword');
	Route::post('user/{user_id}/change-email', 'UserController@changeEmail');


	Route::get('worker/{user_id}/payment-details/{transaction_id}', 'TransactionController@workerPaymentDetails');

	Route::get('worker/{user_id}/stats', ['uses' => 'TranscriptionJobController@stats', 'as' => 'worker_stats']);

	Route::get('user/{user_id}/orders', ['uses' => 'OrderController@index', 'as' => 'user_orders']);
	Route::get('user/{user_id}/orders/old_file/{order_id}', ['uses' => 'OrderController@oldFileDownload', 'as' => 'user_old_order_download']);

	Route::post('user/{user_id}/add-second-email', 'UserController@addSecondEmail');
	Route::post('user/{user_id}/delete-second-email', 'UserController@deleteSecondEmail');

});



// auth + user has record
$router->group(['middleware' => ['auth', 'user.has_record']], function() {
	Route::get('audios/{id}/upload_file', ['uses' => 'FileController@audioTranscriptionUploadForm', 'as' => 'audio_transcription_upload']);
	Route::get('audio_slices/{id}/upload_file', ['uses' => 'FileController@audioSliceTranscriptionUploadForm', 'as' => 'audio_slice_transcription_upload']);
	Route::post('audios/{id}/upload_file', 'FileController@audioTranscriptionUploadSave');
	Route::post('audio_slices/{id}/upload_file', 'FileController@audioSliceTranscriptionUploadSave');
	Route::get('audios/{id}/upload_subtitle', ['uses' => 'FileController@audioSubtitleUploadForm', 'as' => 'audio_subtitle_upload']);
	Route::post('audios/{id}/upload_subtitle', 'FileController@audioSubtitleUploadSave');
	Route::get('users/{user_id}/orders/{order_id}', ['uses' => 'OrderController@show', 'as' => 'order']);
	Route::get('users/{user_id}/orders/{order_id}/invoice', ['uses' => 'OrderController@invoice', 'as' => 'order_invoice']);

	Route::get('audios/{audio_id}/delete-file', ['uses' => 'AudioController@deleteFile', 'as' => 'delete_audio_file']);
	Route::post('audios/{audio_id}/rate', ['uses' => 'AudioController@rate', 'as' => 'rate_audio']);

	Route::get('upload/complete/{order_id}', ['uses' => 'UploadController@completed', 'as' => 'upload_complete']);
});

// user has record
$router->group(['middleware' => ['user.has_record']], function() {
	Route::get('download/{audio_id}', ['middleware' => 'user.download_audio', 'uses' => 'Admin\AudioController@download']);
});

// TRANSCRIBER
$router->group(['middleware' => 'auth.transcriber'], function() {
	Route::get('transcription_jobs/available', ['uses' => 'TranscriptionJobController@available', 'as' => 'available_transcription_jobs']);
	Route::get('transcription_jobs/in_progress', ['uses' => 'TranscriptionJobController@in_progress', 'as' => 'in_progress_transcription_jobs']);
	Route::get('transcription_jobs/finished', ['uses' => 'TranscriptionJobController@finished', 'as' => 'finished_transcription_jobs']);

	Route::get('transcription_jobs/{audio_slice_id}/take', 'UserController@takeAudioSliceJob');
	Route::get('transcription_jobs/{audio_slice_id}/cancel', 'UserController@cancelAudioSliceJob');
	Route::get('transcription_jobs/{audio_slice_id}/finish', 'UserController@finishAudioSliceJob');

//	Route::get('audio_slice/{id}/edit_comment', ['uses' => 'AudioSliceController@edit_comment', 'as' => 'audio_slice_edit_comment']);
//	Route::post('audio_slice/{id}/edit_comment', ['uses' => 'AudioSliceController@store_comment', 'as' => 'audio_slice_edit_comment']);
});

// EDITOR has transcriber rights
$router->group(['middleware' => 'auth.editor'], function() {
	Route::get('editing_jobs/available', ['uses' => 'EditorJobController@available', 'as' => 'available_for_editing_jobs']);
	Route::get('editing_jobs/in_progress', ['uses' => 'EditorJobController@in_progress', 'as' => 'in_progress_editing_jobs']);
	Route::get('editing_jobs/finished', ['uses' => 'EditorJobController@finished', 'as' => 'finished_editing_jobs']);

	Route::get('editing_jobs/{audio_id}/take', 'UserController@takeAudioJob');
	Route::get('editing_jobs/{audio_id}/cancel', 'UserController@cancelAudioJob');
	Route::get('editing_jobs/{audio_id}/finish', 'UserController@finishAudioJob');

	Route::get('audio_slices/{audio_slice_id}/rate', ['uses' => 'AudioSliceController@rate', 'as' => 'rate_audio_slice']);
	Route::post('audio_slices/{audio_slice_id}/rate', 'AudioSliceController@postRate');
});

// SUBTITLER
$router->group(['middleware' => 'auth.subtitler'], function() {
	Route::get('subtitling_jobs/available', ['uses' => 'SubtitleJobController@available', 'as' => 'subtitling_jobs.available']);
	Route::get('subtitling_jobs/in_progress', ['uses' => 'SubtitleJobController@in_progress', 'as' => 'subtitling_jobs.in_progress']);
	Route::get('subtitling_jobs/finished', ['uses' => 'SubtitleJobController@finished', 'as' => 'subtitling_jobs.finished']);

	Route::get('subtitling_jobs/{audio_id}/take', 'UserController@takeSubtitlingJob');
	Route::get('subtitling_jobs/{audio_id}/cancel', 'UserController@cancelSubtitlingJob');
	Route::get('subtitling_jobs/{audio_id}/finish', 'UserController@finishSubtitlingJob');

//	Route::get('audio_slices/{audio_slice_id}/rate', ['uses' => 'AudioSliceController@rate', 'as' => 'rate_audio_slice']);
//	Route::post('audio_slices/{audio_slice_id}/rate', 'AudioSliceController@postRate');
});

// TRANSCRIBER or EDITOR
$router->group(['middleware' => 'auth.transcriber_or_editor'], function() {
//	Route::get('set_paypal_email', ['uses' => 'AdminUserController@editPayPalEmail', 'as' => 'set_paypal_email']);
//	Route::post('set_paypal_email', 'AdminUserController@updatePayPalEmail');
});

// ADMIN has all rights
$router->group(['middleware' => 'auth.admin'], function() {
	Route::get('admin/orders', ['uses' => 'AdminController@orders', 'as' => 'admin_orders']);
	Route::get('admin/orders/{order_id}/delete', ['uses' => 'OrderController@delete', 'as' => 'delete_order']);
	Route::get('admin/audios', ['uses' => 'AdminController@audios', 'as' => 'admin_audios']);
	Route::get('admin/audios/{audio_id}/edit', ['uses' => 'AdminAudioController@edit', 'as' => 'admin.audios.edit']);
    Route::post('admin/audios/{audio_id}/update', ['uses' => 'AdminAudioController@update', 'as' => 'admin.audios.update']);
	Route::get('admin/audio_slices', ['uses' => 'AdminController@audioSlices', 'as' => 'admin_audio_slices']);
    Route::get('admin/audio-slices/{audio_slice_id}/edit', ['uses' => 'AdminAudioSliceController@edit', 'as' => 'admin.audio-slices.edit']);
    Route::post('admin/audio-slices/{audio_slice_id}/update', ['uses' => 'AdminAudioSliceController@update', 'as' => 'admin.audio-slices.update']);
    Route::get('admin/audio-subtitles', ['uses' => 'AdminController@audioSubtitles', 'as' => 'admin_audio-subtitles']);
	Route::get('admin/clients', ['uses' => 'AdminController@clients', 'as' => 'admin_clients']);
	Route::get('admin/transcribers', ['uses' => 'AdminController@transcribers', 'as' => 'admin_transcribers']);
	Route::get('admin/editors', ['uses' => 'AdminController@editors', 'as' => 'admin_editors']);
	Route::get('admin/subtitlers', ['uses' => 'AdminController@subtitlers', 'as' => 'admin_subtitlers']);
	Route::get('admin/configuration', ['uses' => 'AdminController@configuration', 'as' => 'admin_config']);
	Route::get('admin/project_stats', ['uses' => 'AdminController@projectStats', 'as' => 'admin_project_stats']);
	Route::get('admin/salaries', ['uses' => 'SalaryController@index', 'as' => 'admin_salaries']);
	Route::post('admin/salaries/pay/{user_id}', ['uses' => 'SalaryController@pay', 'as' => 'admin_salaries_pay']);
	Route::get('admin/client_ratings', ['uses' => 'AdminController@clientRatings', 'as' => 'admin_client_ratings']);

	// Bonuses
	Route::get('admin/bonuses', ['uses' => 'BonusController@index', 'as' => 'admin.bonuses.index']);
	Route::get('admin/bonuses/create', 'BonusController@create');
	Route::post('admin/bonuses', 'BonusController@store');
	Route::get('admin/bonuses/{id}/destroy', 'BonusController@destroy');

	// Language
	Route::get('language/create', 'LanguageController@create');
	Route::post('language', 'LanguageController@store');
	Route::get('language/{id}', ['uses' => 'LanguageController@show', 'as' => 'language']);
	Route::get('language/{id}/edit', 'LanguageController@edit');
	Route::post('language/{id}', 'LanguageController@update');
	Route::get('language/{id}/destroy', 'LanguageController@destroy');

	// Rating Delay
	Route::get('rating_delays/create', 'RatingDelayController@create');
	Route::post('rating_delays', 'RatingDelayController@store');
	Route::get('rating_delays/{id}', ['uses' => 'RatingDelayController@show', 'as' => 'rating_delays']);
	Route::get('rating_delays/{id}/edit', 'RatingDelayController@edit');
	Route::post('rating_delays/{id}', 'RatingDelayController@update');
	Route::get('rating_delays/{id}/destroy', 'RatingDelayController@destroy');

	// Tat
	Route::get('tat/create', 'TatController@create');
	Route::post('tat', 'TatController@store');
	Route::get('tat/{id}', ['uses' => 'TatController@show', 'as' => 'tat']);
	Route::get('tat/{id}/edit', 'TatController@edit');
	Route::post('tat/{id}', 'TatController@update');
	Route::get('tat/{id}/destroy', 'TatController@destroy');

	// Timestamping
	Route::get('timestamping/create', 'TimestampingController@create');
	Route::post('timestamping', 'TimestampingController@store');
	Route::get('timestamping/{id}', ['uses' => 'TimestampingController@show', 'as' => 'timestamping']);
	Route::get('timestamping/{id}/edit', 'TimestampingController@edit');
	Route::post('timestamping/{id}', 'TimestampingController@update');
	Route::get('timestamping/{id}/destroy', 'TimestampingController@destroy');

	// User
	Route::get('admin/user/{id}', ['uses' => 'AdminUserController@show', 'as' => 'admin_user']);
	Route::get('admin/user/{id}/edit', ['uses' => 'AdminUserController@edit', 'as' => 'admin.user.edit']);
	Route::post('admin/user/{id}', 'AdminUserController@update');
	Route::get('admin/user/{id}/undelete', ['uses' => 'AdminUserController@undelete', 'as' => 'admin.users.undelete']);

	Route::get('admin/coupons', ['uses' => 'CouponController@index', 'as' => 'admin_coupons']);
	Route::get('admin/coupons/create', 'CouponController@create');
	Route::post('admin/coupons', 'CouponController@store');
	Route::get('admin/coupons/{id}', ['uses' => 'CouponController@show', 'as' => 'admin_coupon']);
	Route::get('admin/coupons/{id}/edit', 'CouponController@edit');
	Route::post('admin/coupons/{id}', 'CouponController@update');
	Route::get('admin/coupons/{id}/destroy', 'CouponController@destroy');

	Route::get('email_export', ['uses' => 'EmailExportController@index', 'as' => 'email_export']);
	Route::get('email_export/transcriber_emails', 'EmailExportController@transcriberEmails');
	Route::get('email_export/editor_emails', 'EmailExportController@editorEmails');
	Route::get('email_export/client_emails', 'EmailExportController@clientEmails');
	Route::get('email_export/deleted_user_emails', 'EmailExportController@deletedUserEmails');
	Route::get('email_export/not_agreed_to_receive_newsletter', 'EmailExportController@notAgreedToReceiveNewsletterEmails');

	Route::get('admin/pay-worker', 'AdminController@payWorker');

	Route::get('admin/late-audios', ['uses' => 'AdminController@lateAudios', 'as' => 'admin_late_audios']);
	Route::get('admin/late-audio-slices', ['uses' => 'AdminController@lateAudioSlices', 'as' => 'admin_late_audio_slices']);

	Route::get('admin/slice-no-rating-delay', ['uses' => 'NoRatingDelayController@edit', 'as' => 'admin_slice_no_rating_delay']);
	Route::post('admin/slice-no-rating-delay', 'NoRatingDelayController@update');

	// blog
	Route::get('admin/blogs', ['uses' => 'AdminBlogController@index', 'as' => 'admin_blogs']);
	Route::get('admin/blogs/create', 'AdminBlogController@create');
	Route::post('admin/blogs', 'AdminBlogController@store');
	Route::get('admin/blogs/{id}', ['uses' => 'AdminBlogController@show', 'as' => 'admin_blog']);
	Route::get('admin/blogs/{id}/edit', 'AdminBlogController@edit');
	Route::post('admin/blogs/{id}', 'AdminBlogController@update');
	Route::get('admin/blogs/{id}/destroy', 'AdminBlogController@destroy');

	// faq
	Route::get('admin/faqs', ['uses' => 'AdminFAQController@index', 'as' => 'admin_faqs']);
	Route::get('admin/faqs/create', 'AdminFAQController@create');
	Route::post('admin/faqs', 'AdminFAQController@store');
	Route::get('admin/faqs/{id}', ['uses' => 'AdminFAQController@show', 'as' => 'admin_faq']);
	Route::get('admin/faqs/{id}/edit', 'AdminFAQController@edit');
	Route::post('admin/faqs/{id}', 'AdminFAQController@update');
	Route::get('admin/faqs/{id}/destroy', 'AdminFAQController@destroy');

	// services
	Route::get('admin/services', ['uses' => 'AdminServiceController@index', 'as' => 'admin_services']);
	Route::get('admin/services/create', 'AdminServiceController@create');
	Route::post('admin/services', 'AdminServiceController@store');
	Route::get('admin/services/{id}', ['uses' => 'AdminServiceController@show', 'as' => 'admin_service']);
	Route::get('admin/services/{id}/edit', 'AdminServiceController@edit');
	Route::post('admin/services/{id}', 'AdminServiceController@update');
	Route::get('admin/services/{id}/destroy', 'AdminServiceController@destroy');

	// reviews
	Route::get('admin/reviews', ['uses' => 'ReviewController@adminIndex', 'as' => 'admin_reviews']);
	Route::get('admin/reviews/{review_id}/confirm', 'ReviewController@adminConfirm');
	Route::get('admin/reviews/{review_id}/delete', 'ReviewController@adminDelete');

	Route::get('login-as/{user_id}', ['uses' => 'AdminController@loginAs', 'as' => 'login_as']);

    // chats
    Route::resource('admin/messages', 'AdminMessageController');

	// wysiwyg
	Route::any('wysiwyg/images', ['as'=>'imglist', 'middleware'=>'auth', 'uses'=>'AdminBlogController@imageList']);
	Route::any('wysiwyg/upload', ['middleware' => 'auth', 'uses' =>'AdminBlogController@upload']);
});



// example CRUD
Route::get('crud', ['uses' => 'CRUDController@index', 'as' => 'cruds']);
Route::get('crud/create', 'CRUDController@create');
Route::post('crud', 'CRUDController@store');
Route::get('crud/{id}', ['uses' => 'CRUDController@show', 'as' => 'crud']);
Route::get('crud/{id}/edit', 'CRUDController@edit');
Route::post('crud/{id}', 'CRUDController@update');
Route::get('crud/{id}/destroy', 'CRUDController@destroy');

Route::get('selenium/add-file', function() {

	$link = 'https://www.youtube.com/watch?v=uSFQrPzSAnE';
	$audio = youtubeAudio($link);
	$audio = \App\Audio::checkAndSaveAudio($audio);

	return redirect()->route('upload');
});

Route::get('artisan/migrate', function() {

	$output = new \Symfony\Component\Console\Output\BufferedOutput();

	\Illuminate\Support\Facades\Artisan::call('migrate', array('--force' => true), $output);
//		Artisan::call('migrate:refresh', array(), $output);
//		Artisan::call('db:seed', array(), $output);

	$text = $output->fetch();

	echo $text;
});

//use Illuminate\Support\Facades\App;
//App::error(function(Illuminate\Session\TokenMismatchException $exception, $code)
//{
//	/*
//	|    Write to a specific log
//	|    Or write the request information to the database for e.g. a firewall mechanism
//	|
//	|    Or just:
//	*/
//
//	$errors = [
//		'_token' => [
//			'Token mismatch error'
//		]
//	];
//
//	/**
//	 * Generate a new token for more security
//	 */
//	Session::regenerateToken();
//
//	/**
//	 * Redirect to the last step
//	 * Refill any old inputs except _token (it would override our new token)
//	 * Set the error message
//	 */
//	return Redirect::back()->withInput(Input::except('_token'))->withErrors($errors);
//});