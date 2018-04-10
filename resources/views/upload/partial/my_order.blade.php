{{--<div class="height-45 line-height-45 bg-grey text-white semibold font-size-17 text-center">--}}
    {{--MY ORDER--}}
{{--</div>--}}

{{--<div class="row">--}}
    {{--<div class="col-md-12">--}}
        {{--<div class="padding-15 bg-lightgrey">--}}
            {{--@yield('grey_content')  grey content here--}}

            {{--@foreach ($order->audios as $audio)--}}
                {{--<div class="overflow-hidden ellipsis font-size-15 semibold text-grey"><div class="inline-block text-nowrap">{{ $audio->original_filename }}</div></div>--}}
            {{--@endforeach--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}

{{--< ?php--}}
{{--$client_price = round($order->clientPrice(), 2);--}}
{{--? >--}}

{{--@if (isset($user) && $user->discount()->discount)--}}
    {{--<div class="upload__order-summary text-grey semibold">--}}
        {{--<span class="line-height-35 font-size-17">Subtotal:</span>--}}
        {{--<span class="pull-right line-height-35 font-size-30">$<span class="js-subtotal">{{ $client_price }}</span></span>--}}
    {{--</div>--}}
    {{--<hr class="hr hr-lightgrey">--}}
    {{--<div class="upload__order-summary margin-bottom-18 text-grey semibold">--}}
        {{--<div class="line-height-23 font-size-15 md-font-size-13">You've got <span class="js-discount">{{ $user->discount()->discount }}</span>% discount!</div>--}}
        {{--<div class="line-height-35 font-size-23 md-font-size-18">YOU SAVE: $<span class="js-you-save">0.00</span>!</div>--}}
    {{--</div>--}}
    {{--<hr class="hr hr-lightgrey">--}}
{{--@endif--}}
{{--<div class="upload__order-summary  text-grey semibold">--}}
    {{--<span class="line-height-35 font-size-20">Total:</span>--}}
    {{--<span class="pull-right line-height-35 font-size-35">$<span class="js-total">{{ $client_price  }}</span></span>--}}
{{--</div>--}}

{{--@if (isset($show_deliver_estimate))--}}
    {{--<hr class="hr hr-lightgrey">--}}
    {{--<div class="upload__order-summary text-grey semibold">--}}
        {{--<div class="line-height-23 font-size-16 md-font-size-13">Delivery Estimate:</div>--}}
        {{--<div class="line-height-35 font-size-25 md-font-size-18">{{ $order->tat->days * 24 }} hours or less</div>--}}
    {{--</div>--}}
{{--@endif--}}

{{-- Order summary --}}
<div class="order-summary">

    <div class="header"><h3>Order summary</h3></div>

    <div class="table-row">
        @foreach ($order->audios as $audio)
            <div class="filename" title="{{ $audio->original_filename }}">{{ $audio->original_filename }}</div>
        @endforeach
    </div>

    {{-- Discount --}}
    {{--@if ($discount['value'])--}}
        {{--<div class="js-discount-container">--}}
            {{--<div class="table-row table-row-dark order-subtotal-price">--}}
                {{--<span class="subtotal-label">Subtotal:</span>--}}
                {{--<span class="subtotal-price result">$<span class="js-subtotal">{{ round($subtotal, 2) }}</span></span>--}}
            {{--</div>--}}

            {{--<div class="table-row table-row-dark order-discount">--}}
                {{--You've got <span class="js-discount">{{ $discount['value'] }}</span>% discount!--}}
                {{--<div class="discount-save">--}}
                    {{--You save:--}}
                    {{--<strong>$<span class="js-you-save">{{ round($you_save, 2) }}</span></strong>!--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--@endif--}}

    <div class="table-row table-row-dark order-total-price">
        <span class="total-label">Total:</span>
        <span class="total-price result">$<span class="js-total">{{ round($order->clientPrice(), 2) }}</span></span>
    </div>

    <div class="table-row table-row-dark delivery-estimate">
        Delivery Estimate:<br>
        <strong>
            @if ($order->tat->days == 1)
                24 hours or less
            @else
                {{ $order->tat->days }} days or less
            @endif
        </strong>
    </div>
</div>

<div class="order-help-info">
    <span>Do you need help?</span><br>
    Explore our <a target="_blank" href="{{ route('faq') }}">FAQ</a>
    or <a target="_blank" href="{{ route('contacts') }}">Contact us</a>
</div>

