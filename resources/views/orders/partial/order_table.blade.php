@if (!$orders->isEmpty())
    <table class="order-table">
        <thead>
            <tr>
                <th>Order</th>
                <th>Placed</th>
                <th>Status</th>
                <th>Type</th>
                <th>Size</th>{{-- all files --}}
                <th>Cost</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td><a href="{{ route('order', ['user_id' => $order->user->id, 'order_id' => $order->id]) }}">{{ $order->number }}</a></td>
                    <td>{{ date("m-d-y", strtotime($order->paid_at)) }}</td>
                    <td>
                        @if ($order->finished)
                            <span class="icon uploaded-icon" data-toggle="tooltip" data-placement="auto" title="Your order is completed!">
                        @else
                            <span class="icon uploading-icon" data-toggle="tooltip" data-placement="auto" title="Your order is in progress!">
                        @endif
                    </td>
                    <td>Transcription</td>
                    <td>{{ duration($order->totalAudioDuration()) }}</td>
                    <td>@if ($order->client_payment) ${{ $order->client_payment->amount }} @endif</td>
                </tr>
            @endforeach
        </tbody>

        @if ($showPagination)
            @if ($orders->lastPage() > 1)
                <tfoot>
                    <tr>
                        <th colspan="6">
                            @include('pagination.default', ['paginator' => $orders])
                        </th>
                    </tr>
                </tfoot>
            @endif
        @endif
    </table>
@else
    <div class="">You have no orders right now</div>
@endif