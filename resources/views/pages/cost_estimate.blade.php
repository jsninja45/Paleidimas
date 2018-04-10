@extends('app')

@section('title'){{ 'Audio transcription cost estimate' }}@stop

@section('content')
    <div class="cost-estimate-body">
        <div class="container">
            <h3 class="page-heading">@yield('title')</h3>

            <p>
                Are you curious how much would it cost to turn your audio to text, but you don't have the time to
                negotiate or contact the customer support? Well, when it comes to audio transcriptions, time is money
                both in metaphorical and literal senses. On this page you can easily and precisely calculate how much
                the time of your audio is worth in money. Just tick the boxes to mark your preferences and instantly see
                the cost estimate of your audio file. Try it out for free!
            </p>

            <div class="row">
                <div class="col-xs-12 col-lg-9">
                    <div class="js-calculator order-settings">

                        {{-- Recording length --}}
                        <div class="setting">
                            <div class="setting-name">
                                <span class="icon text-format-icon"></span>
                                Recording length
                            </div>

                            <div class="options">
                                <div class="col-xs-12 col-sm-6">
                                    <input id="recordingHours" class="form-control js-number js-hours js-autoupdate" min="0" type="number" name="hours" value="0" data-toggle="popover" data-target="#nextDiscountNotice" data-trigger="manual" data-placement="top">
                                    <label for="recordingHours">Hours</label>
                                    <div id="nextDiscountNotice" style="display:none">
                                        <div class="js-next-discount">Transcribe 0 minutes more and receive 0 discount.</div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-6">
                                    <input id="recordingMinutes" class="form-control js-number js-minutes js-autoupdate" min="0" type="number" name="minutes" value="0">
                                    <label for="recordingMinutes">Minutes</label>
                                </div>
                            </div>
                        </div>

                        {{-- Text format --}}
                        <div class="setting">
                            <div class="setting-name">
                                <span class="icon text-format-icon"></span>
                                Text format
                            </div>

                            @include('components.calculator.partial.text_format')
                        </div>

                        {{-- Timestamping --}}
                        <div class="setting">
                            <div class="setting-name">
                                <span class="icon timestamping-icon"></span>
                                Timestamping
                                <span class="icon help-icon" data-toggle="tooltip" title="Timestamping helps to keep track of the progression of the audio. In other words, our transcribers can mark preferred time points of the audio into the text."></span>
                            </div>

                            @include('components.calculator.partial.timestamping')
                        </div>

                        {{-- Turnaround time --}}
                        <div class="setting">
                            <div class="setting-name turnaround-time">
                                <span class="icon turnaround-icon"></span>
                                Turnaround time
                            </div>
                            @include('components.calculator.partial.turnaround_time')
                        </div>

                        {{-- Number of speakers --}}
                        <div class="setting">
                            <div class="setting-name speakers">
                                <span class="icon speakers-icon"></span>
                                Number of speakers
                            </div>
                            @include('components.calculator.partial.number_of_speakers')
                        </div>

                        {{-- Subtitles and CC --}}
                        <div class="setting">
                            <div class="setting-name subtitles">
                                <span class="icon subtitles-icon"></span>
                                Subtitles and CC
                            </div>
                            @include('components.calculator.partial.subtitles')
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-lg-3">
                    <div class="order-summary js-calculator">

                        <div class="header"><h3>Summary</h3></div>

                        {{-- Discount --}}
                        <div class="js-discount-container" @if (!$order->discount()['value']) style="display:none" @endif>
                            <div class="table-row table-row-dark order-subtotal-price">
                                <span class="subtotal-label">Subtotal:</span>
                                <span class="subtotal-price result">$<span class="js-subtotal">0.00</span></span>
                            </div>

                            <div class="table-row table-row-dark order-discount">
                                You've got <span class="js-discount">{{ $order->discount()['value'] }}</span>% discount!
                                <div class="discount-save">
                                    You save:
                                    <strong>$<span class="js-you-save">0.00</span></strong>!
                                </div>
                            </div>
                        </div>

                        <div class="table-row table-row-dark order-total-price">
                            <span class="total-label">Total:</span>
                            <span class="total-price result">$<span class="js-total">0.00</span></span>
                        </div>
                    </div>

                    <a class="button-medium button-icon button-neon button-full-width button-order" href="{{ route('upload') }}">
                        <span class="icon cart-icon"></span>
                        Proceed to order
                    </a>
                </div>
            </div>

        </div>
    </div>
@stop

@section('scripts')
    @parent

    <script>
        $(function() {
            $('.js-autoupdate:first').change();
        });

        function calculatorUpdateHTML(data) {
            // output
            var calculator = $(".js-calculator");

            var subtotal_input = calculator.find(".js-subtotal");
            subtotal_input.text(round(data['subtotal'], 2));

            var discount = calculator.find(".js-discount");
            discount.text(round(data['discount']));

            var you_save_input = calculator.find(".js-you-save");
            you_save_input.text(round(data['you_save'], 2));

            var total_input = calculator.find(".js-total");
            total_input.text(round(data['total'], 2));

            if (data['next_discount'] && data['minutes'] != 0) {
                $('#recordingHours').popover('show');
                $(".popover-content .js-next-discount").text('Transcribe ' + round(data['next_discount']['minutes'] - data['discount_minutes']) + ' minutes more and receive ' + data['next_discount']['percent'] + '% discount.');
            } else {
                $('#recordingHours').popover('hide');
            }

            if (data['discount'] > 0) {
                $('.js-discount-container').slideDown();
            } else {
                $('.js-discount-container').slideUp();
            }
        }

        // calculator
        @include('components.calculator.partial.js.calculatorjs')
    </script>
@stop
