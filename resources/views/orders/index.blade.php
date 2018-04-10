@extends('app')

@section('title'){{ 'Order history' }}@stop

@section('content')

    <div class="order-history-body">
        <div class="container">
            <h3 class="page-heading">Order history</h3>


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

                    {{-- Table --}}
                    @include('orders.partial.order_table', array_merge(compact('orders'), ['showPagination' => true]))

                    {{-- Old orders --}}
                    @if (count($old_orders) > 0)
                        <h3 class="page-heading">Old orders</h3>

                        <table class="order-table">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Placed</th>
                                <th>Status</th>
                                <th>Length</th>
                                <th>Download</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($old_orders as $order)
                                @if (in_array($order->job_status, [2, 3, 4]))
                                    <tr>
                                        <td>{{ $order->a_filename }}</td>
                                        <td>{{ date("F jS, Y", $order->timestamp) }}</td>
                                        <td>
                                            @if ($order->job_status == 3)
                                                <span class="icon uploaded-icon" data-toggle="tooltip" data-placement="auto" title="Your order is completed!"></span>
                                            @else
                                                <span class="icon uploading-icon" data-toggle="tooltip" data-placement="auto" title="Your order is in progress!"></span>
                                            @endif
                                        </td>
                                        <td>{{ $order->a_lenght }}</td>
                                        <td>
                                            @if ($order->job_status == 3)
                                                <a target="_blank" href="{{ route('user_old_order_download', ['user_id' => $user, 'order_id' => $order->jid]) }}">
                                                    Download
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    @endif


                </div>

                <div class="col-xs-12 col-md-3">
                    <a class="button-medium button-neon button-icon button-full-width button-new-order" href="{{ route('upload') }}">
                        <span class="icon upload-icon"></span>
                        Place new order
                    </a>

                    <div class="order-summary discount-summary">
                        <div class="header"><h3>Discount summary</h3></div>
                        <div class="table-row">
                            <div class="current-discount result">{{ $discount->percent }}%</div>
                            <span>Your current discount</span>
                        </div>
                        @if ($next_discount)
                            <div class="table-row table-row-dark receive-discount">
                                <div class="discount-label">
                                    Upload {{ round($next_discount->minutes - $paid_minutes) }} more minutes to receive
                                </div>
                                <div class="result">{{ $next_discount->percent }}% DISCOUNT</div>
                            </div>
                        @endif
                    </div>

                    <div class="order-help-info">
                        <span>Do you need help?</span><br>
                        Explore our <a target="_blank" href="{{ route('faq') }}">FAQ</a>
                        or <a target="_blank" href="{{ route('contacts') }}">Contact us</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop