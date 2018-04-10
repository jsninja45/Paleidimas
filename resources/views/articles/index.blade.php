@extends('app')

@section('title'){{ 'Blog' }}@stop

@section('content')
    <div class="blog-body">
        <div class="container">
            <h3 class="page-heading">@yield('title')</h3>

            <div class="row">
                <div class="col-xs-12 col-md-8">

                    <div class="posts">
                        @include('articles.partial.articles', [
                            'articles' => $articles,
                            'article_bg' => 'bg-lightgrey',
                        ])
                    </div>

                    @include('pagination.default', ['paginator' => $articles])
                </div>

                <div class="col-xs-12 col-md-4">
                    <a href="{{ route('upload') }}">
                        <img class="img-responsive center-block" src="{{ asset('img/blog-sidebar-ad.png') }}" alt="">
                    </a>
                </div>


            </div>
        </div>
    </div>
@stop