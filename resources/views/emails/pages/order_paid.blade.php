@extends('emails.template')

@section('content')


    <p style="">Thanks for your order. We'll start working on it right away.</p>
    {!! emailButton($order->link('invoice'), 'download receipt') !!}

    <h2 style="">Order details:</h2>

    <table style="">
        <tr>
            <td>Order number:</td>
            <td>{{ $order->number }}</td>
        </tr>
        <tr>
            <td>Price:</td>
            <td>${{ $order->client_payment->amount }}</td>
        </tr>
        <tr>
            <td>Customer:</td>
            <td>{{ $order->user->email }}</td>
        </tr>
        <tr>
            <td>Payment date:</td>
            <td>{{ $order->paid_at }}</td>
        </tr>
    </table>

@stop