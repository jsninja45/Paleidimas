@foreach ($articles as $article)

    <div class="post">
        @if ($article->hasImage())
            <div class="post-image">
                <a href="{{ $article->link() }}">
                    <img src="{{ $article->imageUrl() }}" alt="">
                </a>
            </div>
        @endif

        <div class="post-info clearfix">
            <div class="info-item date">
                {{ owl_date($article->created_at) }}
            </div>
        </div>

        <h2 class="post-title">
            <a href="{{ $article->link() }}">
                {{  $article->title }}
            </a>
        </h2>

        <div class="post-content">
            <p>{{ str_limit(strip_tags(html_entity_decode($article->content)), $limit = 275, $end = '...') }}</p>
            <a href="{{ $article->link() }}" class="read-more">Continue reading</a>
        </div>
    </div>

@endforeach
