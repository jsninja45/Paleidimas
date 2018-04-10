@extends('emails.template')

@section('content')

    <?php
    $transcriber_ids = \App\Audio::find(1)->first()->slices->lists('transcriber_id');
    $transcriber_emails = \App\User::find($transcriber_ids)->lists('email');
    ?>

    <h2 style="">Hello,</h2>

    <p style="">Client rated job {{ $audio->original_filename }} and evaluated it.</p>
    <p style="">Editor: {{ $audio->editor->email }}</p>
    <p style="">Transcribers: {{ implode(', ', $transcriber_emails) }}</p>
    <p style="">Transcription rate: {{ $audio->rating }} out of 5</p>
    <p style="">Comment: {{ $audio->rating_comment }}</p>

@endsection