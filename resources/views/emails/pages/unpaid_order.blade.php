@extends('emails.template')

@section('content')

    <h2 style="">Hello,</h2>
    <p style="">
        We noticed that you uploaded a file for transcription, but haven’t paid for it yet. We thought we’d remind you that we cannot start working with any of the files until they are paid for. For more information please visit <a style="" href="{{ route('faq') }}">{{ route('faq') }}</a> . If we can help you in any way, please let us know.
    </p>

@stop