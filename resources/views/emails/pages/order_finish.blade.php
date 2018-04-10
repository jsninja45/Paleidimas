@extends('emails.template')

@section('content')

    <p style="">Great News! Your order is completed. You can check your order below:</p>
    {!! emailButton($order->link(), 'check order') !!}

    <p style="">We appreciate your business!</p>

@stop