@extends('emails.template')

@section('content')

    <p style="">
        Order number: {{ $order->number }}<br>
        Payment amount: {{ $order->client_payment->amount }}<br>
        Client e-mail: {{ $order->user->email }}<br>
        Length: {{ duration($order->minutes() * 60) }}<br>
        Text format: {{ $order->textFormat->name }}<br>
        Timestamping: {{ $order->timestamping->name }}<br>
        Subtitles: {{ $order->subtitle->name }}<br>
        Number of speaker: {{ $order->speaker->name }}<br>
        Turnaround: {{ $order->tat->days }} days<br>
        Language: {{ $order->language->name }}<br>
    </p>

@endsection