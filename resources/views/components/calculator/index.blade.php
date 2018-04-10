<div class="row gutter-10 js-calculator">


    <div class="col-md-9 collapse">
        <div class="relative margin-bottom-5 padding-top-10 padding-bottom-10 padding-left-5 padding-right-5 bg-orange text-center font-size-20 text-white border-radius-3">
            <div class="js-next-discount">Transcribe 0 minutes more and receive 0 discount.</div>
            <div class="calculator__orrange-arrow-down"></div>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-9">

        <div class="calculator__border">
            <div class="row">
                <div class="col-xs-2">
                    <div class="bg-lightgrey text-center line-height-52 xs-line-height-100">
                        <img class="inline-block img-responsive" src="/img/components/calculator/1.png" alt="">
                    </div>
                </div>
                <div class="col-xs-10">
                    <div class="row margin-top-13">
                        <div class="col-sm-4">
                            <div class="calculator__title">Recording length</div>
                        </div>

                        <div class="collapse visible-xs height-5"></div>

                        <div class="col-xs-4 col-sm-4 col-lg-3">

                            <div class="inline-block pull-left width-84 text-center line-height-28 semibold font-size-15 text-grey visible-xs">Hours</div>
                            <div class="clearfix visible-xs"></div>

                            <div class="calculator__input-container js-number-container">
                                <div class="calculator__arrow-left-container js-decrease">
                                    <div class="calculator__arrow-left"></div>
                                </div>
                                <input class="calculator__input js-number js-hours js-autoupdate" type="text" name="hours" value="0">
                                <div class="calculator__arrow-right-container js-increase">
                                    <div class="calculator__arrow-right"></div>
                                </div>
                            </div>
                            <div class="inline-block pull-left margin-left-8 line-height-28 semibold font-size-15 text-grey hidden-xs">Hours</div>

                         </div>
                        <div class="col-xs-4 col-sm-4 col-lg-3">

                            <div class="inline-block pull-left width-84 text-center line-height-28 semibold font-size-15 text-grey visible-xs">Minutes</div>
                            <div class="clearfix visible-xs"></div>

                            <div class="calculator__input-container js-number-container">
                                <div class="calculator__arrow-left-container js-decrease">
                                    <div class="calculator__arrow-left"></div>
                                </div>
                                <input class="calculator__input js-number js-minutes js-autoupdate" type="text" name="minutes" value="0">
                                <div class="calculator__arrow-right-container js-increase">
                                    <div class="calculator__arrow-right"></div>
                                </div>
                            </div>
                            <div class="inline-block pull-left margin-left-8 line-height-28 semibold font-size-15 text-grey hidden-xs">Minutes</div>

                        </div>
                    </div>
                </div>
            </div>

            <hr class="hr hr-white">

            <div class="row">
                <div class="col-xs-2">
                    <div class="bg-lightgrey text-center line-height-52 xs-line-height-110">
                        <img class="inline-block img-responsive" src="/img/components/calculator/3.png" alt="">
                    </div>
                </div>
                @include('components.calculator.partial.text_format')
            </div>

            <hr class="hr hr-white">

            <div class="row">
                <div class="col-xs-2">
                    <div class="bg-lightgrey text-center line-height-90 xs-line-height-140">
                        <img class="inline-block img-responsive" src="/img/components/calculator/6.png" alt="">
                    </div>
                </div>
                @include('components.calculator.partial.timestamping')
            </div>

            <hr class="hr hr-white">

            <div class="row">
                <div class="col-xs-2">
                    <div class="bg-lightgrey text-center line-height-52 xs-line-height-110">
                        <img class="inline-block img-responsive" src="/img/components/calculator/5.png" alt="">
                    </div>
                </div>
                @include('components.calculator.partial.number_of_speakers')
            </div>

            <hr class="hr hr-white">

            <div class="row">
                <div class="col-xs-2">
                    <div class="bg-lightgrey text-center line-height-52 xs-line-height-170">
                        <img class="inline-block img-responsive" src="/img/components/calculator/4.png" alt="">
                    </div>
                </div>
                @include('components.calculator.partial.turnaround_time')
            </div>

            <hr class="hr hr-white">

            <div class="row">
                <div class="col-xs-2">
                    <div class="bg-lightgrey text-center line-height-52 xs-line-height-100">
                        <img class="inline-block img-responsive" src="/img/components/calculator/2.png" alt="">
                    </div>
                </div>
                @include('components.calculator.partial.language')
            </div>

        </div>
    </div>
    <div class="col-md-3">

        {{-- price --}}
        <div class="padding-20 calculator__border xs-margin-top-10 sm-margin-top-10">
            <div class="margin-bottom-18 text-grey semibold">
                <span class="line-height-35 font-size-17">Subtotal:</span>
                <span class="pull-right line-height-35 font-size-35">$<span class="js-subtotal">0.00</span></span>
            </div>
            <hr class="hr hr-lightgrey">
            <div class="margin-top-18 margin-bottom-18 text-grey semibold">
                <div class="line-height-23 font-size-15 md-font-size-13">You've got <span class="js-discount">{{ $order->discount()['value'] }}</span>% discount!</div>
                <div class="line-height-35 font-size-23 md-font-size-18">YOU SAVE: $<span class="js-you-save">0.00</span>!</div>
            </div>
            <hr class="hr hr-lightgrey">
            <div class="margin-top-18 margin-bottom-13 text-grey semibold">
                <span class="line-height-35 font-size-20">Total:</span>
                <span class="pull-right line-height-35 font-size-35">$<span class="js-total">0.00</span></span>
            </div>
        </div>

        <div class="text-center">
            <a class="button button--green button--small-padding button--cart margin-top-10 width-full md-font-size-16" href="{{ route('upload') }}">Proceed to order</a>
        </div>



    </div>

</div>


{{-- simple popover --}}
<div class="hidden js-simple-popover-template">
    <div class="popover popover-blue" role="tooltip">
        <div class="arrow"></div>
        <h3 class="popover-title"></h3>
        <div class="popover-content"></div>
    </div>
</div>


<script src="/generated/calculator.js"></script>

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

        var next_discount = $(".js-next-discount");
        if (data['next_discount'] && data['minutes'] != 0) {
            next_discount.text('Transcribe ' + round(data['next_discount']['minutes'] - data['discount_minutes']) + ' minutes more and receive ' + data['next_discount']['percent'] + '% discount.');
            next_discount.parent().parent().slideDown();
        } else {
            next_discount.parent().parent().slideUp();
        }
    }

</script>
