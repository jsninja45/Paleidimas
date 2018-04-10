<div class="options clearfix">
    <div class="col-xs-12 col-sm-6">
        <div class="form-dropdown">
            <select class="js-autoupdate" name="language">
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}" @if ($order->language->id == $language->id) selected @endif>{{ $language->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>