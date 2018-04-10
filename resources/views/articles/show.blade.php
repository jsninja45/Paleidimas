@extends('app')

@section('title'){{ $article->title }}@stop

@section('breadcrumb')
    <span><a href="{{ route('blog') }}">Blog</a></span>
@stop

@section('content')
    <div class="blog-body">
        <div class="container">

            <div class="row">
                <div class="col-xs-12 col-md-8">
                    <h1 class="page-heading">@yield('title')</h1>

                    <div class="post single-post">
                        @if ($article->hasImage())
                            <div class="post-image">
                                <img src="{{ $article->imageUrl() }}" alt="">
                            </div>
                        @endif

                        <div class="post-info clearfix">
                            <div class="info-item date">
                                {{ owl_date($article->created_at) }}
                            </div>
                        </div>

                        <div class="post-content">
                            {!! $article->content !!}
                        </div>

                        {{-- Social media --}}
                        <div class="social-share">
                            <div class="social-button">
                                <div class="fb-share-button" data-layout="button_count"></div>
                            </div>
                            <div class="social-button">
                                <a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
                            </div>
                            <div class="social-button">
                                <div class="g-plusone" data-size="medium"></div>
                            </div>
                        </div>
                    </div>

                    <div class="post-comments">
                        <div id="disqus_thread"></div>
                        <script type="text/javascript">
                            /* * * CONFIGURATION VARIABLES * * */
                            var disqus_shortname = 'speechtotext';

                            /* * * DON'T EDIT BELOW THIS LINE * * */
                            (function() {
                                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                                dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                            })();
                        </script>
                        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-xs-12 col-md-4">
                    <a href="{{ route('upload') }}">
                        <img class="img-responsive center-block" src="{{ asset('img/blog-sidebar-ad.png') }}" alt="">
                    </a>

                    <div class="news-widget">
                        <h4>Newest posts</h4>

                        @foreach ($news as $article)
                            <div class="post clearfix">
                                <div class="post-image">
                                    <a href="{{ $article->link() }}">
                                        <img src="{{ $article->imageUrl() }}" alt="">
                                    </a>
                                </div>

                                <h3 class="post-title">
                                    <a href="{{ $article->link() }}">
                                        {{ $article->title }}
                                    </a>
                                </h3>

                                <div class="post-content">
                                    <p>
                                        {{ str_limit(strip_tags(html_entity_decode($article->content)), $limit = 100, $end = '...') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop