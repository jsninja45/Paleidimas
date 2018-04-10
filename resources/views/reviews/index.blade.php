@extends('app')

@section('title'){{ 'Feedback' }}@stop

@section('content')
    <div class="container feedback-body">
        <h1>Feedback about our transcription service</h1>

        <p class="page-intro">
            We look forward to receiving your suggestions. This will allow us to respond to your needs
            and expectations more efficiently in the future. We value our clients and the 605,010 minutes of audio we
            have transcribed so far.
        </p>

        <!-- Video feedback -->
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="feedback-video">
                    <div class="profile">
                        <div class="author">Bob</div>
                        <div class="profession">- Writer</div>
                    </div>

                    <div class="video">
                        <iframe src="//www.youtube.com/embed/WD0cOZhenr8?rel=0&amp;showinfo=0&amp;modestbranding=1"
                                frameborder="0" width="274" height="180"></iframe>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-4">
                <div class="feedback-video">
                    <div class="profile">
                        <div class="author">Johnatan Chase</div>
                        <div class="profession">- Journalist</div>
                    </div>

                    <div class="video">
                        <iframe src="//www.youtube.com/embed/L-IRMQMN0U4?rel=0&amp;showinfo=0&amp;modestbranding=1"
                                frameborder="0" width="274" height="180"></iframe>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-4">
                <div class="feedback-video">
                    <div class="profile">
                        <div class="author">Harry</div>
                        <div class="profession">- Film maker</div>
                    </div>

                    <div class="video">
                        <iframe src="//www.youtube.com/embed/z6pMqvUHdXQ?rel=0&amp;showinfo=0&amp;modestbranding=1"
                                frameborder="0" width="274" height="180"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Site rating -->
            <div class="col-xs-12 col-lg-3">
                <div class="site-rating">
                    <div class="number">{{ round($data['average_rating'], 1) }}</div>
                    <div class="label">Site rating</div>
                </div>
            </div>

            <!-- Filtering -->
            <div class="col-xs-12 col-lg-9">
                <div class="filtering">
                    <label class="label">Filter by:</label>

                    @for ($i = 5; $i > 0; $i--)
                        <a href="{{ route('reviews') }}?rating={{ $i }}" class="rating">
                            {{-- Set active checkbox --}}
                            @if ((int) Request::get('rating') === $i)
                                <div class="checkbox active"></div>
                            @else
                                <div class="checkbox"></div>
                            @endif

                            @for ($s = 0; $s < $i; $s++)
                                <span class="star"></span>
                            @endfor
                        </a>
                    @endfor

                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <!-- Feedback -->
        <div class="feedback-list">
            @foreach ($reviews as $key => $review)
                @if ($key % 2 === 0)
                    <div class="row">
                @endif

                <div class="col-xs-12 col-md-6">
                    <div class="feedback">
                        <div class="author">{{ $review->name }}</div>
                        <div class="header">
                            <div class="rating">
                                @for ($i = 0; $i < $review->rating; $i++)
                                    <div class="rating-star"></div>
                                @endfor
                            </div>
                            <div class="date">{{ owl_date($review->created_at) }}</div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="body">
                            {{ $review->content }}
                        </div>
                    </div>
                </div>
                @if ($key % 2 !== 0 OR ($key + 1) === count($reviews))
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pagination-container">
            @include('pagination.default', ['paginator' => $reviews->appends(Input::except('page'))])
        </div>

        <!-- Form -->
        <h3>Leave Feedback about our transcription service</h3>
        <p>
            We want your feedback about your experience, service, and product purchases at Crown Vision Center.
            Feedback is how we will grow as a company and improve our relationship with you the customer.
            We take pride in our business and want to make sure every patient leaves happy.
        </p>

        <div class="form-wrapper">
            <form method="post" action="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="row">
                    <div class="col-xs-12">
                        @if ($errors->has())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif (Session::has('message'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{{ Session::get('message') }}</li>
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div class="col-xs-12 col-md-7">
                        <div class="form-group">
                            <input class="form-control" type="text" name="name" placeholder="Your or Company name" value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-5">
                        <div class="form-group clearfix">
                            <span class="label">Rate our service</span>
                            <div class="feedback-rating">
                                <input type="hidden" name="rating">
                                @for ($i = 0; $i < 5; $i++)
                                    <div class="rating-star"></div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <textarea class="form-control" name="content" placeholder="Your feedback">{{ old('content') }}</textarea>
                        </div>

                        <button class="button pull-right" type="submit">Submit review</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@stop