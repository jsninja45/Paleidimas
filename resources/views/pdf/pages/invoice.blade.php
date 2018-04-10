@extends('pdf.template')

@section('content')

    <h2 style="">Company</h2>
    <p style="">@include('components.company_info')</p>

    <h2 style="">Order Details</h2>
    <table border="0" cellspacing="0" cellpadding="5">
        <tr>
            <td>Order number:</td>
            <td>{{ $order->number }}</td>
        </tr>
        {{--<tr>--}}
            {{--<td>Order placed:</td>--}}
            {{--<td>{{ $order->paid_at }}</td>--}}
        {{--</tr>--}}
        <tr>
            <td>Duration:</td>
            <td>{{ secondsToTime($order->minutes() * 60) }}</td>
        </tr>
        <tr>
            <td>Price:</td>
            <td>${{ $order->client_payment->amount }}</td>
        </tr>
        @if ($order->coupon)
            <tr>
                <td>Coupon:</td>
                <td>{{ $order->coupon->code }}</td>
            </tr>
        @endif
    </table>

    <h2 style="">Customer details</h2>
    <p style="">Email: {{ $order->user->email }}</p>

    <h2 style="">Payment details</h2>

        @if ($order->client_payment)
            @if ($order->client_payment->payment_type === 'paypal')
                    Charged To: Paypal, {{ $order->client_payment->payment_name }}, {{ $order->client_payment->payment_email }}<br />
            @endif


            @if ($order->client_payment->payment_type === 'creditcard')
                <p style="">
                    Charged To: Credit Card, {{ $order->client_payment->payment_name }}, xxxx-xxxx-xxxx-{{ $order->client_payment->payment_creditcard }}
                </p>
            @endif

                Charged On: {{ date('d/m/Y', strtotime($order->paid_at)) }}
        @endif




@stop

@section('footer')
    <p style="">
        OwlTranscription.com<br />
        info@owltranscription.com
    </p>
@endsection