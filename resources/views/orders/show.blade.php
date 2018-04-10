@extends('app')

@section('title'){{ $order->number }}@stop

@section('breadcrumb')
    <span><a href="{{ route('user_orders', [$order->user->id]) }}">Order history</a></span>
@stop

@section('content')
    <div class="order-preview-body">
    <div class="container">


        <div class="row">
            <div class="col-xs-12 col-md-4">
                <h3 class="page-heading">{{ $order->number }}</h3>
            </div>

            <div class="col-xs-12 col-md-8">
                <div class="social-share">
                    <div class="social-label">Recommend our service</div>

                    <div class="social-button facebook">
                        <div class="fb-like" data-href="{{ URL::to('/') }}" data-layout="button_count" data-action="recommend" data-show-faces="false" data-share="true"></div>
                    </div>

                    <div class="social-button twitter">
                        <a href="https://twitter.com/share" class="twitter-share-button" data-url="{{ URL::to('/') }}" data-text="Best Speech to Text service -">Tweet</a>
                    </div>

                    <div class="social-button google-plus">
                        <div class="g-plus" data-action="share" data-annotation="bubble" data-href="{{ URL::to('/') }}"></div>
                    </div>

                    <div class="social-button linked-in">
                        <script type="IN/Share" data-counter="right" data-url="{{ URL::to('/') }}" ></script>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @include('orders.partial.audio_table', ['order' => $order, 'audios' => $order->audios])
            </div>
        </div>
    </div>
@stop

@section('scripts')
    @parent

    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=470903049677788";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, "script", "facebook-jssdk"));
    </script>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>
    <script >
        window.___gcfg = {
            lang: "en_US",
            parsetags: "onload"
        };
    </script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script src="//platform.linkedin.com/in.js" type="text/javascript">
        lang: en_US
    </script>
@stop