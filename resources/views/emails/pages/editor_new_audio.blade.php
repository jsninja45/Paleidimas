@extends('emails.template')

@section('content')

    <h2 style="">Hello,</h2>
    <p style="">
        New file is transcribed on <a href="{{ URL::to('/') }}" style="color:#f7a82b;">https://speechtotextservice.com</a>. Please log in and start working on it.
    </p>

@endsection