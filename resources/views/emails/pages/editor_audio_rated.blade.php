@extends('emails.template')

@section('content')

    <h2 style="">Hello,</h2>

    <p style="">Client rated your job {{ $audio->original_filename }} and evaluated it.</p>
    <p style="">Transcription rate: {{ $audio->rating }} out of 5</p>
    <p style="">Comment: {{ $audio->rating_comment }}</p>

@endsection