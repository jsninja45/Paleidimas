<div class="options clearfix">
    <div class="col-xs-12 col-sm-3">
        <div class="checkbox radio">
            <input class="js-autoupdate" type="radio" id="days_1" name="tat" value="days_1" @if (isset($order->tat->slug) && $order->tat->slug === 'days_1') checked @endif>
            <label for="days_1">1 Day</label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-3">
        <div class="checkbox radio">
            <input class="js-autoupdate" type="radio" id="days_3" name="tat" value="days_3" @if (isset($order->tat->slug) && $order->tat->slug === 'days_3') checked @endif>
            <label for="days_3">3 Days</label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-3">
        <div class="checkbox radio">
            <input class="js-autoupdate" type="radio" id="days_7" name="tat" value="days_7" @if (isset($order->tat->slug) && $order->tat->slug === 'days_7') checked @endif>
            <label for="days_7">7 Days</label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-3">
        <div class="checkbox radio">
            <input class="js-autoupdate" type="radio" id="days_14" name="tat" value="days_14" @if (isset($order->tat->slug) && $order->tat->slug === 'days_14') checked @endif>
            <label for="days_14">14 Days</label>
        </div>
    </div>
</div>