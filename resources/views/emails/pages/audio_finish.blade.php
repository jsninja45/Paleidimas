@extends('emails.template')

@section('content')

    <p center style="">Great News! Your transcription is ready for download.</p>
    {!! emailButton($audio->transcription->link(), 'download file') !!}
    <p center style="">We appreciate your business! </p>

@stop