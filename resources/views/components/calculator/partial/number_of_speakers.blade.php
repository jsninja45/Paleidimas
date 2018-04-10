<div class="options clearfix">
    <div class="col-xs-12 col-sm-4">
        <div class="checkbox radio">
            <input class="js-autoupdate" type="radio" id="max_2" name="speakers" value="max_2" @if (isset($order->speaker->slug) && $order->speaker->slug === 'max_2') checked @endif>
            <label for="max_2">1-2</label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4">
        <div class="checkbox radio">
            <input class="js-autoupdate" type="radio" id="min_3" name="speakers" value="min_3" @if (isset($order->speaker->slug) && $order->speaker->slug === 'min_3') checked @endif>
            <label for="min_3">3 and more</label>
        </div>
    </div>
</div>