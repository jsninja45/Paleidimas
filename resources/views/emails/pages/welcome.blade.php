@extends('emails.template')

@section('content')

    <h2 style="margin: 0; font-size: 19px; font-weight: bold;">Hello!</h2>

    <p style="margin: 25px 0 20px 0;">Have you heard of SpeechToTextService?</p>

    <table>
        <tr>
            <td style="margin: 0; vertical-align: top;">
                <img src="{{ URL::to('/img/email/star.png') }}" style="margin-top: 5px; margin-right: 12px; margin-left: 12px;">
            </td>
            <td>
                <p style="margin: 0; line-height: 22px;">It provides highly accurate transcripts done by a professional teamwith a timely and convenient delivery. </p>
            </td>
        </tr>
    </table>
    <table style="margin-top: 12px;">
        <tr>
            <td style="vertical-align: top;">
                <img src="{{ URL::to('/img/email/star.png') }}" style="margin-top: 5px; margin-right: 12px; margin-left: 12px;">
            </td>
            <td>
                <p style="margin: 0; line-height: 22px;">We have unmatched rates and uncompromising quality. We alsoprovide 3minute long Free Trials!</p>
            </td>
        </tr>
    </table>


    <h1 style="margin-bottom: 24px; margin-top: 50px; text-align: center; font-size: 23px; font-weight: bold; line-height: 32px;">Few reasons of why you will love SpeechToTextService</h1>
    <p style="margin: 0px; line-height: 27px; text-align: justify;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>

@stop

@extends('footer'))
    <p style="">We invite you to try our services!</p>
    <p style="">
        Sincerely,<br>
        The <a style="" target="_blank"  href="http://speechtotextservice.com">SpeechToTextService.com</a> Team
    </p>
@stop