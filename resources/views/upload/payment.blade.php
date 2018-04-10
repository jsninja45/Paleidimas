@extends('app')

@section('title')
    Payment
@stop

@section('content')
    <div class="upload-body payment-body">
        <div class="container">

            <h3 class="heading">Payment information</h3>

            <div class="row">
                <div class="col-xs-12">
                    <div class="upload-header">
                        @include('upload.partial.progress', ['step' => 3])
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-lg-9">
                    <form class="js-payment-form" method="post" autocomplete="off">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        @if ($errors->has())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif (Session::has('error'))
                            <div class="alert alert-danger">
                                <ul><li>{{ Session::get('error') }}</li></ul>
                            </div>
                        @endif

                        <div class="payment-container">
                            {{-- Cards --}}
                            <div class="checkbox">
                                <input type="radio" id="creditcard" name="payment_type" value="creditcard">
                                <label for="creditcard"><img src="{{ asset('img/credit-cards.gif') }}" height="30px" title="" alt=""></label>
                            </div>

                            <div class="payment-form js-credit-card-info" style="display:none">
                                <div class="row">

                                    {{-- Card number --}}
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="creditcard_number">Card number <span class="required">*</span></label>
                                            <input class="form-control" id="creditcard_number" type="number" name="transaction[credit_card][number]">
                                        </div>
                                    </div>

                                    {{-- Expire date --}}
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="row">
                                            {{-- Expiration month --}}
                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label for="expire_month">Expire date <span class="required">*</span></label>
                                                    <div class="form-dropdown">
                                                        <select id="expire_month" name="transaction[credit_card][expiration_month]">
                                                            @for ($i = 1; $i < 13; $i++)
                                                                @if ($i > 9)
                                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                                @else
                                                                    <option value="{{ $i }}">0{{ $i }}</option>
                                                                @endif
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Expiration year --}}
                                            <div class="col-xs-6">
                                                <div class="form-group form-expire">
                                                    <label for="expire_year">&nbsp;</label>
                                                    <div class="form-dropdown">
                                                        <select id="expire_year" name="transaction[credit_card][expiration_year]">
                                                            @for ($i = date('Y'); $i < date('Y') + 30; $i++)
                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- CVV --}}
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="cvv">CVV <span class="required">*</span></label>
                                            <input class="form-control" id="cvv" type="number" name="transaction[credit_card][cvv]">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Email --}}
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="first_name">Email <span class="required">*</span></label>
                                            <input class="form-control" id="first_name" type="text" name="transaction[customer][email]" value="{{ $user->email }}">
                                        </div>
                                    </div>

                                    {{-- First name --}}
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="first_name">First name <span class="required">*</span></label>
                                            <input class="form-control" id="first_name" type="text" name="transaction[customer][first_name]">
                                        </div>
                                    </div>

                                    {{-- Last name --}}
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="form-group">
                                            <label for="last_name">Last name <span class="required">*</span></label>
                                            <input class="form-control" id="last_name" type="text" name="transaction[customer][last_name]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="checkbox">
                                <input type="radio" id="paypal" name="payment_type" value="paypal" checked>
                                <label for="paypal"><img src="{{ asset('/img/paypal-amex.png') }}" height="30px" title="" alt=""></label>
                            </div>
                        </div>

                        <div class="payment-discount">
                            <div class="row">
                                <div class="col-xs-12 col-lg-3">
                                    <label for="discountCode">Discount coupon code:</label>
                                </div>

                                <div class="col-xs-12 col-lg-4">
                                    <input class="form-control js-coupon js-bad-coupon-popover" id="discountCode" type="text" name="coupon" value="@if ($order->coupon) {{ $order->coupon->code }} @endif" data-content="This coupon code is invalid or has expired">
                                </div>

                                <div class="col-xs-12 col-lg-push-1 col-lg-4">
                                    <button class="button-medium button-order">Place order</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-xs-12 col-lg-3">

                    {{-- Order summary --}}
                    <div class="order-summary">

                        <div class="header"><h3>Order summary</h3></div>

                        <div class="table-row">
                            @foreach ($order->audios as $audio)
                                <div class="filename" title="{{ $audio->original_filename }}">{{ $audio->original_filename }}</div>
                            @endforeach
                        </div>

                        {{-- Discount --}}
                        <div class="js-discount-container"  @unless ($discount['value']){{ 'style="display:none;"' }}@endunless>
                            <div class="table-row table-row-dark order-subtotal-price">
                                <span class="subtotal-label">Subtotal:</span>
                                <span class="subtotal-price result">$<span class="js-subtotal">{{ round($subtotal, 2) }}</span></span>
                            </div>

                            <div class="table-row table-row-dark order-discount">
                                You've got
                                <span class="js-discount" data-discount="{{ $discount['value'] }}">
                                    {{ $discount['value'] }}%
                                </span> discount!
                                <div class="discount-save">
                                    You save:
                                    <strong>$<span class="js-you-save">{{ round($you_save, 2) }}</span></strong>!
                                </div>
                            </div>
                        </div>

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

                    <div class="order-updates-recipients">
                        Send order updates to:<br>
                        <div class="email">{{ $user->email }}</div>
                        <div class="email js-second-email-container" @if (!$user->second_email) style="display:none" @endif>
                            <span class="js-second-email">{{ $user->second_email }}</span>
                            <span class="js-delete-second-email delete-link" alt="">x</span>
                        </div>
                    </div>

                    <div class="js-add-recipient-container" @if ($user->second_email) style="display:none" @endif>
                        <div class="js-show-add-recipient">
                            <div class="order-add-recipient">
                                <span class="icon add-recipient-icon"></span>
                                Add recipient
                            </div>
                        </div>

                        <form class="js-add-recipient-form" style="display:none">
                            <div class="form-group">
                                <input type="email" class="form-control" placeholder="Second email" name="second_email">
                            </div>

                            <div class="form-group clearfix">
                                <button class="button-medium button-order button-order-recipient pull-right js-add-recipient">Save</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>

        </div>
    </div>

    <script>

        $(function() {
            $(".js-payment-form").submit(formValidation);
            $(".js-coupon").on("input", setDiscount);

            $('.js-show-add-recipient').click(showAddRecipientForm);
            $('.js-add-recipient').click(addRecipient);
            $('.js-delete-second-email').click(deleteSecondEmail);

            $('[name=payment_type]').click(paymentTypeChange);


            $(".js-add-recipient-form").on('click', '.input-error', function() {
                $(this).removeClass('input-error')
            });

            function paymentTypeChange() {
                var type = $(this).val();

                if (type === 'creditcard') {
                    $('.js-credit-card-info').slideDown();
                } else {
                    $('.js-credit-card-info').slideUp();
                }
            }

            $('.js-bad-coupon-popover').popover({
                html: true,
                container: 'body',
                template: '<div class="tooltip top" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner popover-content"></div></div>',
                placement: 'bottom',
                trigger: 'manual'
            });

            function formValidation(e) {

                // form redirect path
                var payment_type = $(this).find("[name=payment_type]:checked");
                if (payment_type.val() === 'paypal') {
                    var action = "{{ route('paypal') }}";
                } else if (payment_type.val() === 'creditcard') {
                    var action = "{{ route('braintree_send') }}";
                } else {
                    throw('payment type error');
                }
                $(this).attr('action', action);


                var total =  parseFloat($(".js-total").text());
                if (total === 0.0) { // used coupon, and doesnt need to pay
                    return true;
                }


                if (payment_type.val() === "creditcard") {
                    var creditcard_number = $(this).find("[name='transaction[credit_card][number]']");
                    if (inputEmptyError(creditcard_number)) {
                        e.preventDefault();
                    }

                    var cvv = $(this).find("[name='transaction[credit_card][cvv]']");
                    if (inputEmptyError(cvv)) {
                        e.preventDefault();
                    }

                    var first_name = $(this).find("[name='transaction[customer][first_name]']");
                    if (inputEmptyError(first_name)) {
                        e.preventDefault();
                    }

                    var last_name = $(this).find("[name='transaction[customer][last_name]']");
                    if (inputEmptyError(last_name)) {
                        e.preventDefault();
                    }
                }

                // if in iframe (affiliate)
                if (isInIframe()) {
                    var form = $('.js-payment-form');
                    form.attr("target","_blank"); // open paypal in new page

                    setTimeout(function() { // dont show payment page
                        window.location = '/';
                    }, 1000);
                }

            }


            var timeout = null;

            function setDiscount() {

                $('.js-bad-coupon-popover').popover('hide');

                var code = $(this).val();

                var subtotal = $(".js-subtotal");
                var total = $(".js-total");

                var data = {
                    _token: "{{ csrf_token() }}",
                    coupon: code
                };


                // delay
                if (timeout !== null) {
                    clearTimeout(timeout);
                }
                timeout = setTimeout(function() {

                    // my code
                    var jqxhr = $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: '/coupon/check',
                        data: data
                    });
                    jqxhr.done(function(data) {
                        // total
                        var total = data['total'];
                        $(".js-total").text(round(total, 2));

                        if (data['coupon_value'] || total != subtotal) {
                            // is discount
                            $(".js-discount-container").slideDown();

                            var discount = '';
                            if (data['coupon_type'] === 'amount') {
                                discount = "$" + round(data['coupon_value'], 2);
                            } else if (data['coupon_type'] === 'percent') {
                                discount = round(data['coupon_value']) + "%";
                            } else {
                                discount = $(".js-discount").data('discount') + '%';
                            }

                            $(".js-discount").text(discount);
                            $(".js-you-save").text(round(data['you_save'], 2));
                        } else {
                            // no discount
                            $(".js-discount-container").slideUp();
                        }

                        // bad coupon popover
                        if ($.trim(code) !== '' && data['coupon_value'] == 0) {
                            $('.js-bad-coupon-popover').popover('show');
                        } else {
                            $('.js-bad-coupon-popover').popover('hide');
                        }


                    });

                }, 300);

            }

            function showAddRecipientForm() {
                $('.js-add-recipient-form').slideToggle();
            }

            function addRecipient(e) {
                e.preventDefault();

                var form = $(this).closest('form');
                var second_email = form.find('[name=second_email]');

                if (!isEmail(second_email.val())) {
                    second_email.addClass('input-error');
                    return;
                }

                $('.js-add-recipient-container').slideUp();

                $('.js-second-email').text(second_email.val());
                $('.js-second-email-container').slideDown();

                var data = {
                    _token: "{{ csrf_token() }}",
                    second_email: second_email.val()
                };
                $.post('/user/{{ $user->id }}/add-second-email', data);
            }

            function deleteSecondEmail() {
                $('.js-second-email-container').slideUp();
                $('.js-add-recipient-form').hide();
                $('.js-add-recipient-container').slideDown();

                var data = {
                    _token: "{{ csrf_token() }}"
                };
                $.post('/user/{{ $user->id }}/delete-second-email', data);
            }

        });

    </script>



@stop