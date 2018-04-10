<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Best Speech to Text service') | Speech to Text</title>

    @section('stylesheets')
        <link href="//fonts.googleapis.com/css?family=Doppio+One" rel="stylesheet" type="text/css">
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    @show

    @yield('head')

</head>
<body class="@yield('bodyClass', '')">

    @include('header')

	@yield('content')

    @include('footer')

	@section('scripts')
        <!-- Scripts -->
        {{--<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>--}}
        <script src="{{ asset('/js/vendor.js') }}"></script>
        <script src="{{ asset('/js/app.js') }}"></script>
    @show


    {{-- social --}}
    <style>iframe[name=google_conversion_frame]{display:none}</style>
    <script type="text/javascript">
        /* <![CDATA[ */
        var google_conversion_id = 958793313;
        var google_custom_params = window.google_tag_params;
        var google_remarketing_only = true;
        /* ]]> */
    </script>
    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
    <noscript>
        <div style="display:inline;">
            <img height="1" width="1" style="border-style:none;" alt=""
                 src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/958793313/?value=0&amp;guid=ON&amp;script=0"/>
        </div>
    </noscript>


    <script>(function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=470903049677788";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

    <script type="text/javascript">
        (function () {
            var po = document.createElement('script');
            po.type = 'text/javascript';
            po.async = true;
            po.src = 'https://apis.google.com/js/platform.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(po, s);
        })();
    </script>

    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
    <!-- SessionCam Client Integration v6.0 -->
    <script type="text/javascript">
        //<![CDATA[
        /* var scRec=document.createElement('SCRIPT');
         scRec.type='text/javascript';
         scRec.src="//d2oh4tlt9mrke9.cloudfront.net/Record/js/sessioncam.recorder.js";
         document.getElementsByTagName('head')[0].appendChild(scRec);*/
        //]]>
    </script>

    {{-- google analytic --}}
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-24038916-1', 'auto');
        ga('send', 'pageview');
    </script>

    @yield('analytics-ecommerce')

    <!--Start of Zopim Live Chat Script-->
    <script type="text/javascript">
        window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
                d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
                _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
            $.src="//v2.zopim.com/?24cT3UDvPbiiPgHBLBwPPhn8modKVJPW";z.t=+new Date;$.
                    type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
    </script>
    <!--End of Zopim Live Chat Script-->

    <!-- SessionCam Client Integration v6.0 -->
    <script type="text/javascript">
        //<![CDATA[
        /* var scRec=document.createElement('SCRIPT');
         scRec.type='text/javascript';
         scRec.src="//d2oh4tlt9mrke9.cloudfront.net/Record/js/sessioncam.recorder.js";
         document.getElementsByTagName('head')[0].appendChild(scRec);*/
        //]]>
    </script>
    <!-- End SessionCam -->


</body>
</html>
