@extends('app')

@section('title')
    Frequently Asked Questions
@stop

@section('content')
    <div class="faq-body">
        <div class="container">
            <h3>@yield('title')</h3>

            <!-- Question links -->
            <ul>
                @foreach ($faqs as $faq)
                    <li>
                        <a href="{{ $faq->link() }}">
                            {{ $faq->question }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="seperator"></div>

            <!-- Answers -->
            @foreach ($faqs as $faq)
                    <h2 id="question-{{ $faq->id }}">{{ $faq->question }}</h2>
                    <p>{!! $faq->answer !!}</p>
            @endforeach
        </div>
    </div>
@stop