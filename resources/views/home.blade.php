@extends('app')

@section('bodyClass'){{ 'footer-no-margin' }}@stop

@section('content')
<!-- Presentation-->
<div class="fluid-section presentation-section">
    <div class="container">
        <h3><a href="{{ route('upload') }}">Upload your file</a> <span>We do the rest!</span></h3>

        <div class="row">
            <div class="col-xs-12 col-md-5 col-lg-6">
                <ul>
                    <li>There will be no grammatical mistakes.</li>
                    <li>Professional transcribers carry out all transcriptions.</li>
                    <li>Professional editors check all transcripts thoroughly. The transcriptions are 99% accurate.</li>
                    <li>
                        Our specialists have a wide range of experience and are capable of dealing with
                        industry-specific terminology, from medical to financial.
                    </li>
                </ul>
            </div>
            <div class="col-xs-12 col-md-7 col-lg-6">
                <div class="laptop-bg">
                    <object width="390" height="238">
                        <param name="movie" value="//www.youtube.com/v/jT7noJzkxkQ?version=3&amp;showinfo=0&amp;rel=0&amp;autohide=1"/>
                        <embed src="//www.youtube.com/v/jT7noJzkxkQ?version=3&amp;showinfo=0&amp;rel=0&amp;autohide=1" type="application/x-shockwave-flash" width="390" height="238"/>
                    </object>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Upload section --}}
@include('pages.partial.upload_section')

{{-- Main content --}}
<div class="fluid-section marketing-section">
    <div class="container">

        <!-- Features -->
        <div class="row feature-icons">
            <div class="col-xs-12 col-md-4 feature feature-1">
                <span class="sprite feature-easy-fast">&ensp;</span>

                <h3>It's easy and fast!</h3>

                <p>Upload Files Directly from Your Computer or Point to Files Already Online.</p>
            </div>

            <div class="col-xs-12 col-md-4 feature feature-2">
                <span class="sprite feature-high-quality">&ensp;</span>

                <h3>High quality</h3>

                <p>We Believe that High Quality Work Keeps Us Close to Our Clients.</p>
            </div>

            <div class="col-xs-12 col-md-4 feature feature-3">
                <span class="sprite feature-cheap">&ensp;</span>

                <h3>Cheap</h3>

                <p>We Have Superior Transcription Processes Allowing Us to Offer the Best Prices on the Market.</p>
            </div>
        </div>

        <!-- Pricing table -->
        @include('pages.partial.pricing_table')

        <!-- Loyalty program -->
        @include('pages.partial.loyalty_program')
    </div>
</div>

{{-- Audio transcription cost estimate --}}
@include('pages.partial.cost_estimate')

{{-- Audio transcription samples --}}
@include('pages.partial.samples_section')

<!-- Latest orders -->
<div class="fluid-section latest-orders-section">
    <div class="container">

        <!-- Heading -->
        <div class="row">
            <div class="col-xs-12 col-md-7">
                <h3 class="heading">Latest orders</h3>
            </div>
        </div>

        <div class="row">

            <!-- Table of orders -->
            <div class="col-xs-12 col-md-7">

                <table>
                    <tr>
                        <th>Date</th>
                        <th>Length</th>
                        <th>Turnaround</th>
                        <th>Text format</th>
                        <th>Timestamping</th>
                    </tr>
                    <tr>
                        <td>1 hour ago</td>
                        <td>01:10:06</td>
                        <td>3 days</td>
                        <td>Clean verbatim</td>
                        <td>No timestamping</td>
                    </tr>
                    <tr>
                        <td>3 hours ago</td>
                        <td>00:48:15</td>
                        <td>3 days</td>
                        <td>Clean verbatim</td>
                        <td>No timestamping</td>
                    </tr>
                    <tr>
                        <td>7 hours ago</td>
                        <td>00:28:10</td>
                        <td>3 days</td>
                        <td>Full verbatim</td>
                        <td>No timestamping</td>
                    </tr>
                    <tr>
                        <td>2 days ago</td>
                        <td>02:10:07</td>
                        <td>7 days</td>
                        <td>Full verbatim</td>
                        <td>No timestamping</td>
                    </tr>
                </table>
            </div>

            <!-- Statistic -->
            <div class="col-xs-12 col-md-5">
                <div class="stat large">
                    <span class="sprite stat-clients"></span>

                    <div class="count">
                        <span>5154</span>

                        <div class="label">Number of clients</div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="stat small">
                    <span class="sprite stat-transcriptions"></span>

                    <div class="count">
                        <span>154620</span>

                        <div class="label">Transcriptions</div>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="stat medium">
                    <span class="sprite stat-hours-transcribed"></span>

                    <div class="count">
                        <span>54770</span>

                        <div class="label">Hours transcribed</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Feedback -->
<div class="fluid-section feedback-section">
    <div class="container">
        <h3 class="heading">Our clients feedback</h3>
        <p>
            We look forward to receiving your suggestions. This will alow us to respond to your needs and expectations
            more efficiently in the future.
        </p>

        <div class="row">
            @foreach ($reviews as $review)
                <div class="col-xs-12 col-md-4">
                    <div class="feedback">
                        <div class="header">
                            <div class="rating">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    <div class="rating-star"></div>
                                @endfor
                            </div>
                            <div class="date">{{ owl_date($review->created_at) }}</div>
                            <div class="author">{{ $review->name }}</div>
                        </div>
                        <div class="body">
                            {{ $review->content }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row">
            <div class="col-xs-12 col-md-offset-4 col-md-4">
                <a href="{{ route('reviews') }}" class="button-large">Read all feedback</a>
            </div>
        </div>
    </div>
</div>

<!-- Free trial -->
<div class="fluid-section free-trial-section">
    <div class="container">
        <h3 class="heading">SpeechToText Free Trial</h3>

        <div class="process-icons">
            <span class="sprite trial-time-icon"></span>
            <span class="line"></span>
            <span class="sprite trial-letter-icon"></span>
            <span class="line"></span>
            <span class="sprite trial-ok-icon"></span>
        </div>

        <p>
            You can now try our services free of charge for the first 3 minutes of your recording.
            <br/>
            All you need to do is write us at
            <a href="mailto:info@speechtotextservice.com">info@speechtotextservice.com</a>
        </p>

        <div class="row">
            <div class="col-xs-12 col-md-offset-4 col-md-4">
                <a href="{{ route('free_trial') }}" class="button-large orange">Try us now!</a>
            </div>
        </div>
    </div>
</div>

<!-- Latest news -->
<div class="fluid-section latest-news-section">
    <div class="container">
        <h3 class="heading">Latest news</h3>

        <div class="row">
            @foreach ($articles->take(3) as $article)
                <div class="col-xs-12 col-md-4">
                    <div class="post">
                        {{-- Image --}}
                        <div class="image">
                            @if ($article->hasImage())
                                <a href="{{ $article->link() }}">
                                    <img class="img-responsive" src="{{ $article->imageUrl() }}" alt="{{ $article->title }}">
                                </a>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h2>
                            <a href="{{ $article->link() }}">{{ $article->title }}</a>
                        </h2>

                        {{-- Date --}}
                        <div class="date">{{ owl_date($article->created_at) }}</div>

                        {{-- Content --}}
                        <div class="body">
                            {{ str_limit(strip_tags(html_entity_decode($article->content)), $limit = 275, $end = '...') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Brands section -->
<div class="fluid-section brands-section">
    <div class="container">
        <h3 class="heading">Trusted by</h3>

        <img class="logos" src="{{ asset('img/images/logos.png') }}">
    </div>
</div>
@stop

@section('scripts')
    @parent

    <script type="text/javascript">
        // resize window
        jQuery(function ($) {
            $(window).on('resize', function () {
                // resize video by screen
                if (window.matchMedia('(max-width: 991px)').matches) {
                    var videoWidth = $(".laptop-bg").width();
                    $(".laptop-bg object, .laptop-bg object embed").height(videoWidth / 1.5847);
                } else {
                    $(".laptop-bg object, .laptop-bg object embed").height(238);
                }
            });

            $(window).trigger('resize');
        });
    </script>
@stop

