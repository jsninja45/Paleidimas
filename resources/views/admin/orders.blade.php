@extends('simple_app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <form method="get" autocomplete="off">
                                <div class="col-md-2">
                                    <input class="form-control" type="text" name="order_id" placeholder="Order ID" value="{{ Input::get('order_id') }}">
                                </div>
                                <div class="col-md-2">
                                    <input class="form-control js-datepicker" type="text" name="date" placeholder="Date" value="{{ Input::get('date') }}">
                                </div>
                                <div class="col-md-2">
                                    <input class="form-control" type="text" name="email" placeholder="Email" value="{{ Input::get('email') }}">
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="from_affiliate" @if (Input::has('from_affiliate')) checked @endif> From affiliate
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="paid" @if (Input::has('paid')) checked @endif> Paid
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <button class="btn btn-primary">Filter</button>
                                    <a href="{{ currentUrl() }}" class="btn btn-default">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">Orders</div>

                    <div class="panel-body">

                        <table class="table table-hover">
                            <tr>
                                <th>Order</th>
                                <th>Client</th>
                                <th>Subtitles</th>
                                <th>Paid</th>
                                <th>Deadline</th>
                                <th>Finished</th>
                                <th>Client paid</th>
                                <th>Coupon</th>
                                <th>Affiliate ID</th>
                                {{--<th>Invoice</th>--}}
                                <th></th>
                                <th></th>
                            </tr>
                            @foreach ($orders as $order)
                                <?php
                                $bg = '';
                                if ($order->finished == 0 && $order->deadline_at) {
                                    $bg = 'bg-warning';
                                    if ($order->deadline_at < date('Y-m-d H:i:s')) {
                                        $bg = 'bg-danger';
                                    }
                                }
                                if ($order->finished == 1) {
                                    $bg = 'bg-success';
                                }
                                ?>
                                <tr class="{{ $bg }}">
                                    <td>{{ $order->number }}</td>
                                    <td>@if ($order->user) {{ $order->user->email }} @endif</td>
                                    <td>{{ $order->subtitle->name }}</td>
                                    <td>@if ($order->paid_at) {{ $order->paid_at }} @else - @endif</td>
                                    <td>@if ($order->deadline_at) {{ $order->deadline_at }} @else - @endif</td>
                                    <td>@if ($order->finished) {{ $order->finished }} @else - @endif</td>
                                    <td>@if ($order->client_price) ${{ $order->client_price }} @endif</td>
                                    <td>@if ($order->coupon) {{ $order->coupon->code }} @endif</td>
                                    <td>@if ($order->user){{ $order->user->affliate_abovealloffers or $order->user->referrer }}@endif</td>
                                    {{--<td>@if ($order->paid_at) <a target="_blank" class="btn btn-default btn-xs" href="{{ $order->link('invoice') }}">Invoice</a> @endif</td>--}}
                                    <td><a class="btn btn-primary btn-xs" href="{{ route('admin_audios') }}?order_id={{ $order->id }}">More</a></td>
                                    <td><a onclick="return confirm('Are you sure?');" class="btn btn-danger btn-xs" href="{{ $order->link('delete') }}">Delete</a></td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="text-center">
                    {!! $orders->appends(Input::except('page'))->render() !!}
                </div>
            </div>

        </div>
    </div>

@stop