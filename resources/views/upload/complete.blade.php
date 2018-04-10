@extends('app')

@section('title')
    Order details
@stop

@section('content')

    <div class="upload-body upload-completed-body">
        <div class="container">
            <h3 class="heading">Order completed</h3>

            <div class="row">
                <div class="col-xs-12">
                    <div class="upload-header">
                        @include('upload.partial.progress', ['step' => 4])
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-9">

                    @if ($errors->has())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Order table --}}
                    @include('orders.partial.order_table', ['showPagination' => false])

                    <div class="row">
                        <div class="col-xs-12 col-sm-push-4 col-sm-4">
                            <a href="{{ route('user_orders', [Auth::id()]) }}" class="button-medium button-yellow button-full-width">Check all orders</a>
                        </div>

                        <div class="col-xs-12 col-sm-push-4 col-sm-4">
                            <a href="{{ route('upload') }}" class="button-medium button-neon button-full-width">Place new orders</a>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-3">
                    <div class="info-box">
                        Do you need help?
                        <hr>
                        Explore our <a class="text-green" target="_blank" href="{{ route('faq') }}">FAQ</a> or <a class="text-green" target="_blank" href="{{ route('contacts') }}">Contact us</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop

@section('analytics-ecommerce')
    <script>
        // analytics ecommerce
        ga('require', 'ecommerce');

        ga('ecommerce:addTransaction', {
            'id': '{{ $orders[0]->client_payment->id }}',
            'affiliation': 'SpeechToText',
            'revenue': '{{ $orders[0]->client_payment->amount }}',
            'shipping': '0',
            'tax': '0',
            'currency': 'USD'
        });

        ga('ecommerce:addItem', {
            'id': '{{ $orders[0]->client_payment->id }}',
            'name': '{{ $orders[0]->id }}}',
            'sku': '{{ $orders[0]->minutes() }}',
            'category': '{{ $orders[0]->tat->slug }}',
            'price': '{{ $orders[0]->client_price }}',
            'quantity': '1'
        });

        ga('ecommerce:send');
    </script>
@stop