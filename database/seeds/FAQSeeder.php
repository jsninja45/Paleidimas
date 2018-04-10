<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\FAQ;

class FAQSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		FAQ::create([
			'question' => 'How do I pay for my transcriptions?',
			'answer' => 'SpeechToTextService accepts all payment methods allowed by PayPal. We also invoice. Contact our Customer Support for details on billing arrangements. Our Email info@speechtotextservice.com',
		]);
		FAQ::create([
			'question' => 'What if my delivery is late?',
			'answer' => 'If we fail to deliver on the date we have committed to, we will fully reimburse you. The only possible obstacles for delivering the file on time are bad quality audio or other technical problems. If any problems do arise, the client is informed immediately.',
		]);
		FAQ::create([
			'question' => 'What if I have a problem with the transcriptions?',
			'answer' => 'Submit any claims to our Customer Support department within seven days of receipt of your materials. You can call, contact our online support or send an email with details.',
		]);
		FAQ::create([
			'question' => 'Can you to transcribe only sections of a recording?',
			'answer' => 'Yes, if you let us know in your order details. When you upload your file, leave us a message with any instructions you think are necessary. We will make sure we understand your instructions. We will contact you with any questions.',
		]);
		FAQ::create([
			'question' => 'What is included in your price calculator estimate?',
			'answer' => 'You give us the length of the recording, your estimate of the category it falls into (see the descriptions summarized on the calculator) and the turnaround time you need. If your entries are accurate, our estimate is correct. Note: Quality of the sound on the recording is critical to the time is takes to complete the work (and the cost to you!). We appreciate realistic estimates.',
		]);
		FAQ::create([
			'question' => 'What affects turnaround time?',
			'answer' => 'A couple of the biggest factors affecting turnaround time:<br><br>

Urgency – standard turnaround times are the cheapest. If you need something expedited, we can do some jobs in one day. The turnaround factor is part of your quote.<br><br>

Recording quality – We do fast work when your audio files are clear and with little background noise or technical problems. Files that are of poor quality are more difficult to transcribe, but we will put our best experts on the job.',
		]);
		FAQ::create([
			'question' => 'How will I receive my transcribed material?',
			'answer' => 'We will email your file in a Microsoft Word file. If you need it in another format, let us know in the comments section (Add transcriber instructions) along the file you upload.',
		]);
		FAQ::create([
			'question' => 'What should I put in the Message/Description/Instructions box when I place my order?',
			'answer' => 'Put anything you think would be helpful to our transcriptionists.
Identify speakers, spell uncommon technical terms and define acronyms.
Provide details on particular sections that you know may be difficult to transcribe.
Tell us if there is anything unusual in the file that we should be aware of, like a long break or a section that you do not want transcribed.
More is better. A good collection of notes can reduce the time it takes to do the transcription, saving you money.',
		]);
		FAQ::create([
			'question' => 'Will you skip ads in the audio file?',
			'answer' => 'Yes, we will skip the ads, and note where the breaks occurred. If you want the ads transcribed, let us know in the instructions with your order.',
		]);
		FAQ::create([
			'question' => 'What audio qualities are important for your transcription?',
			'answer' => 'Overall quality has a big effect on how long it takes us to do the transcription and our ability to be accurate. At a minimum, your audio file must be 44.1 kHz and a loud enough volume for accuracy. The less background noise the better. If you think we might have trouble with your recordings, please contact our Customer Support team. We can provide an upfront evaluation.',
		]);
		FAQ::create([
			'question' => 'What complicates the transcription process?',
			'answer' => 'Poor sound quality is the biggest problem: excessive background noise, noises in the recording process, and not enough volume. Other factors that complicate transcription: more than one speaker, multiple people speaking at the same time, group discussions, fast speaking and industry-specific terminology. If you think any of these apply to your recording, contact our Customer Support team.',
		]);
		FAQ::create([
			'question' => 'Do you place timestamps in the text?',
			'answer' => 'We offer 2 types of timestamp placement. You can choose between having timestamps every 2 minutes or when the speaker changes in the audio file. Timestamping service can be requested during the upload process.',
		]);
		FAQ::create([
			'question' => 'How can I convert my audio file into MP3?',
			'answer' => 'Convert a WAV (audio) file into the MP3 format with iTunes. Open the file in iTunes. Choose “Convert Selection to MP3” in the “Advanced” menu. Indicate where to save the new file. If you don\'t see the option “Convert Selection to MP3”, change your Import Settings in your iTunes Preferences to “MP3 Encoder”.',
		]);
		FAQ::create([
			'question' => 'Do you provide verbatim or edited texts?',
			'answer' => 'All of our texts are Clean verbatim by default. If you require full verbatim text, you can request it during the upload process.',
		]);
		FAQ::create([
			'question' => 'Are you all HIPAA compliant?',
			'answer' => 'Yes, we are. We know how important the patient data is, therefore we are doing everything to ensure its security.',
		]);
		FAQ::create([
			'question' => 'What about Privacy/Confidentiality Agreement?',
			'answer' => 'All of our translators have signed a Confidentiality Agreement. We respect our clients\' privacy, therefore we provide the option for the client to personally remove the completed transcriptions from our database. Regarding our translators, the aforementioned data is completely removed from their computers as well.',
		]);
		FAQ::create([
			'question' => 'What is your refund policy?',
			'answer' => 'The transcript order can be canceled anytime before the scheduled delivery date. The refund is processed in 1 business day. The refund amount however depends on the progress and is the amount paid multiplied by the percent progress of the transcript. Therefore, if the progress is 0% then the full amount will be refunded and lesser as the progress increases. The Work-In-Progress transcript can be saved as a text file from the account.<br><br>

We do not offer refunds after the transcript has been delivered. However we offer free re-reviews once the transcript has been delivered.',
		]);
	}

}