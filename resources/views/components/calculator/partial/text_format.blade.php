<div class="options">
    <div class="col-xs-12 col-sm-6">
        <div class="checkbox radio">
            <input class="js-text-format js-autoupdate" type="radio" id="clean_verbatim" name="text_format" value="clean_verbatim" @if (isset($order->textFormat->slug) && $order->textFormat->slug === 'clean_verbatim') checked @endif>
            <label for="clean_verbatim">Clean verbatim</label>
            <span class="icon help-icon" data-toggle="tooltip" title="The transcribed text does not include speech errors, false starts and various filler words, such as: um, uh, hmm, so, you know, sort of, etc."></span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-6">
        <div class="checkbox radio">
            <input class="js-text-format js-autoupdate" type="radio" id="full_verbatim" name="text_format" value="full_verbatim" @if (isset($order->textFormat->slug) && $order->textFormat->slug === 'full_verbatim') checked @endif>
            <label for="full_verbatim">Full verbatim</label>
            <span class="icon help-icon" data-toggle="tooltip" title="The text is transcribed exactly as it sounds and includes all utterances of the speakers (e.g. Mm-hmm, uh-huh, umm, uh, etc.)."></span>
        </div>
    </div>
</div>