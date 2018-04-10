@extends('app')

@section('title'){{ 'About Us' }}@stop

@section('content')
    <div class="container about-page">
        <div class="row">
            <div class="col-xs-12 col-md-6">

                <div class="content">
                    <h1>About our speech to text service</h1>

                    <p>
                        We have a global staff of professional transcriptionists and editors. Our
                        transcriptionists are highly-trained and must pass our certification processes.
                    </p>

                    <p>
                        We regularly review the work of our transcribers, our editors and our Customer Support team to
                        ensure that we are providing the highest quality products and services found anywhere.
                    </p>

                    <p>
                        We have an extensive staff of technical experts. Many of our transcriptionists have a broad base
                        of experience in our client's areas of expertise. Whether it's healthcare, high-tech, legal,
                        education, media or any other type of industry, we match our professional experience with
                        yours.
                    </p>
                </div>

                <div class="contact-form">
                    <h1>Contact Us</h1>
                    <form method="post" action="{{ route('contacts') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        @if ($errors->has())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif (session()->has('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <input class="form-control" type="text" name="name" value="@if (Auth::check() && Auth::user()->name) {{ Auth::user()->name }} @endif" placeholder="Your name">
                        </div>

                        <div class="form-group">
                            <input class="form-control" type="email" name="email" value="@if (Auth::check() && Auth::user()->email) {{ Auth::user()->email }} @endif" placeholder="Your email">
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" name="content" placeholder="Your message"></textarea>
                        </div>

                        <div class="form-group">
                            <button class="button pull-right">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-xs-12 col-md-6">
                <div class="info">
                    Speech to text service is a U.K.-based company. Our office is located in
                    Edinburgh City.
                </div>

                <div id="googleMap" class="google-map">map</div>

                <div class="contacts">
                    Parker Corporation LP<br/>
                    Registration No.: SL12792<br/>
                    39 Duke Street<br/>
                    Edinburgh EH6 8HH<br/>
                    United Kingdom
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    @parent

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqIx79braEd0hStPPQuzwNu9w3W4WMTic&amp;sensor=false"></script>
    <script type="text/javascript">
        var sptxt = new google.maps.LatLng(55.970157, -3.169789);
        var marker;
        var map;

        function initialize() {
            var mapOptions = {
                zoom: 9,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                center: sptxt
            };

            map = new google.maps.Map(document.getElementById("googleMap"),
                    mapOptions);

            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                animation: google.maps.Animation.DROP,
                position: sptxt
            });
            google.maps.event.addListener(marker, 'click', toggleBounce);
        }

        function toggleBounce() {

            if (marker.getAnimation() != null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@stop