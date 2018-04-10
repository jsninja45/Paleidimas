@extends('app')

@section('title')
    Pricing
@stop

@section('head')
    <script src="/vendor/jquery-circle/js/jquery.circle-diagram.js"></script>{{-- http://www.jqueryscript.net/other/Customizable-Circular-Progress-Bar-with-jQuery-CSS3-Circle-Diagram.html --}}
@stop

@section('content')
    <div class="pricing-body">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <div class="pricing_top_left">
                        <p style="text-align: center;">
                            We believe our pricing is the best you will find
                            anywhere. Our company takes pride in providing products and services at competitive prices. We take our
                            commitment to you very seriously.<br/>The table below provides details of what can impact a higher
                            transcription cost.
                        </p>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4">
                    <div class="price-per-minute">
                        <p>For our loyal customers the price per audio minute can be as low as <strong>$0.64</strong>.</p>
                    </div>
                </div>
            </div>

            <hr/>

            <h1>SPEECH TO TEXT PRICING TABLE</h1>
            @include('pages.partial.pricing_table')
        </div>

        {{-- Audio transcription cost estimate --}}
        @include('pages.partial.cost_estimate')

        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <p>We will need to know three basic factors for your quote:</p>
                    <ol>
                        <li>The length of the recording</li>
                        <li>How many speakers are in the audio file?</li>
                        <li>The urgency of the turnaround time.</li>
                    </ol>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <p>
                        <strong>One-On-One Interview:</strong> Single Speakers. No
                        other speakers are apparent in the audio file except for the interviewer and the interviewee.
                    </p>

                    <p>
                        <strong>Group Discussions:</strong> Multiple speakers with voice overlaps. More than 2
                        people are heard in the audio file, who sometimes speak simultaneously.
                    </p>

                    <p>&nbsp;</p>
                </div>
            </div>

            {{-- Loyalty program --}}
            @include('pages.partial.loyalty_program')

            <hr/>

            <div class="ask-information">
                <p>Ask about our pricing and quality guarantees.</p>
                <p>We'll be happy to explain.</p>
                <p>We promise to do everything possible to be the only transcription service provider you will ever need.</p>
            </div>

            <div class="row why-us">
                <div class="col-xs-12 col-sm-5">
                    <h5>WHY CHOOSE US?</h5>
                    <ul>
                        <li>Affordable Rates</li>
                        <li>High Quality: 99% Accuracy</li>
                        <li>Money Back guarantee</li>
                        <li>Friendly and fast support</li>
                    </ul>
                </div>

                <div class="col-xs-12 col-sm-7 file-upload">
                    <h5>ALL YOU HAVE TO DO IS UPLOAD YOUR FILE</h5>

                    <p>
                        We provide uncompromising quality, rates within your budget, highly accurate transcripts and timely &amp;
                        convenient delivery.
                    </p>

                    <a href="{{ route('upload') }}" class="button-large orange">
                        UPLOAD YOUR FILE NOW!
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop