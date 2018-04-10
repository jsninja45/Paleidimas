@extends('emails.template')

@section('content')

    <h2 style="">Hello,</h2>

    <p style="">Please click button below to reset your SpeechToTextService.com account password. </p>

    {!! emailButton(url('password/reset/' . $token), 'reset password') !!}

    <p style="">This link will expire 24 hours from now and your current password will remain the same. Please ignore this email if you have not requested a password reset. </p>

    {{--{{ url('password/reset/' . $token) }}--}}

@stop
