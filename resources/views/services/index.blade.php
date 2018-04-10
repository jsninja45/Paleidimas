@extends('app')

@section('title'){{ 'Audio transcription service' }}@stop
@section('bodyClass'){{ 'services-page footer-no-margin' }}@stop

@section('content')
    <div class="services-body">
        <h1>Audio transcription service</h1>

        <!-- Propositions -->
        <div class="container">
            <div class="row propositions-section">
                <div class="col-xs-12 col-md-3">
                    <div class="propositions-logo">
                        <img src="{{ asset('/img/services-logo.png') }}" alt="SpeechToTextService.com">
                    </div>
                </div>

                <div class="col-xs-12 col-md-9">
                    <ul>
                        <li>Media choices have grown exponentially in the past few years.</li>
                        <li>Every market is globally competitive.</li>
                        <li>Your business is more complicated, and your work demands greater expediency.</li>
                        <li>You also have more choices of transcription services out here on the Internet.</li>
                    </ul>
                </div>
            </div>
        </div>


        <!-- Service features -->
        <div class="service-features-section">
            <div class="container">
                <p>
                    We recognize these challenges and understand what our customers need.
                    We are looking for clients who do not just want to be satisfied, but want to be delighted!
                    We have the staffing, technology and industry expertise to support
                    all your audio transcription needs. Some of the services we provide include:
                </p>

                <div class="row">
                    <div class="col-xs-12 col-sm-6 service-feature">
                        <i class="sprite feature-coverage"></i>
                        <p>
                            Coverage of all subjects - legal, technical, business,
                            niche markets, media, entertainment, education.
                        </p>
                    </div>

                    <div class="col-xs-12 col-sm-6 service-feature">
                        <i class="sprite feature-support"></i>
                        <p>Support for unique projects such as synchronizing audio with slide presentation.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service quality -->
        <div class="service-quality-section">
            <div class="container">
                <span>
                    From dictation to interviews and, sermons to focus groups, we offer affordable
                    transcription services and meet your deadline without breaking your budget.
                </span>
            </div>
        </div>

        <div class="container">
            <h1 class="services-heading">Our transcribers are experienced in the following:</h1>
            <p class="subheading">Legal transcription for attorneys, lawyers, court proceedings, judges, solicitors, court clerks and police...</p>

            @include('services.partial.services', compact('services'))

            <h1 class="languages-heading">
                Furthermore, we are a Multilingual Transcription
                Service. For the moment, the available languages are:
            </h1>

            <div class="row">
                @foreach ($languages as $language)
                    <div class="col-xs-12 col-sm-6 col-md-4">
                        <div class="language">
                            <div class="image">
                                <img src="{{ $language->imageUrl() }}" alt="{{ $language->name }}">
                            </div>
                            <h3>{{ $language->name }}<br>language</h3>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Upload section --}}
        @include('pages.partial.upload_section')

        {{-- Audio transcription samples --}}
        @include('pages.partial.samples_section')
    </div>
@stop