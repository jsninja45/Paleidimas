@extends('emails.template')

@section('content')

    <h2 style="">Contact us form</h2>

    <p style="">Name: {{ $name }}</p>
    <p style="">Email: {{ $email }}</p>
    <p style="">Content: {{ $content }}</p>

@stop