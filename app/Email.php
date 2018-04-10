<?php namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Email extends Model {

	public static function send($emails, $template, $subject, $compacted_template_data = [])
	{
		$emails = (array)$emails; // string -> array

		foreach ($emails as $email) {

			// is user deleted
			$user = User::where('email', $email)->first();
			if ($user && $user->deleted) {
				continue; // don't send email to deleted user
			}

			// is email passed to function (or null), in the future we can check
			if ($email) {
				Mail::send($template, $compacted_template_data, function($message) use ($email, $subject)
				{
					$message->to($email)->subject($subject);
					$message->replyTo(env('MAIL_EMAIL'));
				});
			}
		}
	}

	// send 2 emails (if second email defined)
	public static function sendClient($user, $template, $subject, $compacted_template_data = [])
	{
		if ($user->email) {
			$email = $user->email;
			self::send($email, $template, $subject, $compacted_template_data);
		}

		if ($user->second_email) {
			$second_email = $user->second_email;
			self::send($second_email, $template, $subject, $compacted_template_data);
		}
	}

	// chunks emails into groups of 25, and queues them
	public static function sendQueuedEmail(Collection $users, $template, $subject, $compacted_template_data = [])
	{
		$chunk_size = env('MAIL_RECIPIENT_LIMIT', 25) - 1; // 1 to + 24 bc = 25 recipients
		foreach ($users->chunk($chunk_size) as $chunked_users) {

			$emails = $chunked_users->lists('email');
			Mail::queue($template, $compacted_template_data, function($message) use ($emails, $subject)
			{
				$bcc_to_email = env('MAIL_BCC_TO_EMAIL', 'change@this.com');
				$from_email = config('mail.from.address');
				$from_name = config('mail.from.name');
				$message->to($bcc_to_email)->replyTo($from_email, $from_name)->bcc($emails)->subject($subject);
			});

		}
	}

	// -------------------------------------------- client -------------------------------------------------------------
	public static function clientOrderPaid(Order $order)
	{
		$user = $order->user;
		$subject = 'Your Transcription Order was Received ' . $order->number;
		$template = 'emails.pages.order_paid';

		//Log::info('email: client order paid');
		self::sendClient($user, $template, $subject, compact('order'));
	}

	public static function clientAudioFinish(Audio $audio)
	{
		$user = $audio->order->user;
		if (!$user->finished_audio_email) {
			return false; // user doesn't want to receive emails about finished audios
		}
		$subject = 'Your transcription for ' . $audio->original_filename . ' is now ready.';
		$template = 'emails.pages.audio_finish';

		self::sendClient($user, $template, $subject, compact('audio'));
	}

	public static function clientOrderFinish(Order $order)
	{
		$user = $order->user;
		$subject = 'Your order ' . $order->number . ' is completed';
		$template = 'emails.pages.order_finish';

		self::sendClient($user, $template, $subject, compact('order'));
	}

	// 24 and 72 hours
	// reminds that user has unpaid files
	public static function clientUnpaidOrderReminder(Order $order)
	{
		$user = $order->user;
		$subject = 'Unpaid files for transcription';
		$template = 'emails.pages.unpaid_order';

		self::sendClient($user, $template, $subject, compact('order'));
	}

	public static function clientUnpaidOrder7Days(Order $order)
	{
		$user = $order->user;
		$subject = 'Your files were removed from our system';
		$template = 'emails.pages.removed_files';

		self::sendClient($user, $template, $subject, compact('order'));
	}

	// --------------------------------------------- transcriber -------------------------------------------------------
	public static function transcriberNewOrder(Order $order)
	{
		$subject = 'New files are waiting for you on speechtotextservice.com';
		$template = 'emails.pages.transcriber_new_order';

		$transcribers = $order->language->users()->transcriber()->receiveNewJobEmails()->get();
		self::sendQueuedEmail($transcribers, $template, $subject);
	}

	public static function transcriberAudioSliceRated(AudioSlice $audio_slice)
	{
		$subject = 'Your transcription of job was evaluated on speechtotextservice.com';
		$template = 'emails.pages.transcriber_audio_slice_rated';

		$transcriber_email = $audio_slice->transcriber->email;

		self::send($transcriber_email, $template, $subject, compact('audio_slice'));
	}

	// --------------------------------------------- editor ------------------------------------------------------------
	public static function editorNewAudio(Audio $audio)
	{
		$subject = 'New files are waiting for you on speechtotextservice.com to edit';
		$template = 'emails.pages.editor_new_audio';

		$editors = $audio->order->language->users()->editor()->receiveNewJobEmails()->get();

		self::sendQueuedEmail($editors, $template, $subject, compact('audio'));
	}

	public static function editorAudioRated(Audio $audio)
	{
		$subject = 'Client rated your edited file ' . $audio->original_filename;
		$template = 'emails.pages.editor_audio_rated';

		$editor_email = $audio->editor->email;

		self::send($editor_email, $template, $subject, compact('audio'));
	}

	// ---------------------------------------- subtitler --------------------------------------------------------------
	public static function subtitlerNewJob(Audio $audio)
	{
		$subject = 'New files are waiting for you on speechtotextservice.com to subtitle';
		$template = 'emails.pages.subtitler_new_job';

		$subtitles = $audio->order->language->users()->subtitlers()->receiveNewJobEmails()->get();

		self::sendQueuedEmail($subtitles, $template, $subject, compact('audio'));
	}

	// --------------------------------------------- admin -------------------------------------------------------------
	public static function adminClientRatedAudio(Audio $audio)
	{
		$subject = 'Client rated edited file ' . $audio->original_filename;
		$template = 'emails.pages.admin_client_rated_audio';

		$admin_emails = User::admin()->lists('email');

		self::send($admin_emails, $template, $subject, compact('audio'));
	}

	public static function adminOrderPaid(Order $order)
	{
		$subject = 'New order received on speechtotextservice.com';
		$template = 'emails.pages.admin_order_paid';

		$admin_emails = User::admin()->lists('email');

		//Log::info('email: admin order paid');
		self::send($admin_emails, $template, $subject, compact('order'));
	}

	// ---------------------------------------------- other ------------------------------------------------------------

	public static function paymentsAreGenerated()
	{
		$subject = 'Payments generated';
		$template = 'emails.pages.payment_generated';

		$transcribers = User::transcriber()->get();

		self::sendQueuedEmail($transcribers, $template, $subject);
	}
}
