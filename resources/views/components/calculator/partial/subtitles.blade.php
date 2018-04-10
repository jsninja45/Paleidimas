<div class="options clearfix">
    <div class="col-xs-12 col-sm-4">
        <div class="checkbox radio">
            <input class="js-autoupdate" type="radio" id="subtitle_not_required" name="subtitle" value="not_required" @if (isset($order->subtitle->slug) && $order->subtitle->slug === 'not_required') checked @endif>
            <label for="subtitle_not_required">Not required</label>
        </div>
    </div>
    <div class="col-xs-12 col-sm-4">
        <div class="checkbox radio">
            <input class="js-autoupdate" type="radio" id="subtitle_srt" name="subtitle" value="srt" @if (isset($order->subtitle->slug) && $order->subtitle->slug === 'srt') checked @endif>
            <label for="subtitle_srt">SRT</label>
        </div>
    </div>
</div>