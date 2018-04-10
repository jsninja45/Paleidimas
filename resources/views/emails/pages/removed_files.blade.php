@extends('emails.template')

@section('content')

    <h2 style="">Hello,</h2>
    <p style="">
        Your files were removed from our system because they were not paid for 7 days. If you want to make order please upload files again to our website <a href="{{ URL::to('/') }}">{{ URL::to('/') }}</a>
    </p>

@stop