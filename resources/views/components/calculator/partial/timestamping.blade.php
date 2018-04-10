<div class="options clearfix">
    <div class="col-xs-12 col-sm-4">
        <div class="checkbox radio">
            <input class="js-autoupdate" type="radio" id="not_required" name="timestamping" value="not_required" @if (isset($order->timestamping->slug) && $order->timestamping->slug === 'not_required') checked @endif>
            <label for="not_required">Not required</label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4">
        <div class="checkbox radio">
            <input class="js-autoupdate" type="radio" id="every_2_minutes" name="timestamping" value="every_2_minutes" @if (isset($order->timestamping->slug) && $order->timestamping->slug === 'every_2_minutes') checked @endif>
            <label for="every_2_minutes">Every 2 minutes</label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4">
        <div class="checkbox radio">
            <input class="js-autoupdate" type="radio" id="change_of_speaker" name="timestamping" value="change_of_speaker" @if (isset($order->timestamping->slug) && $order->timestamping->slug === 'change_of_speaker') checked @endif>
            <label for="change_of_speaker">On speaker change</label>
        </div>
    </div>
</div>