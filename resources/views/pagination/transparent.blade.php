@if ($paginator->lastPage() > 1)
    <ul class="pagination-transparent">
        <li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
            <a class="padding-left-0" href="{{ $paginator->url(1) }}">Previous</a>
        </li>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <li>
                @if ($paginator->currentPage() == $i)
                    {{ $i }}
                @else
                    <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
                @endif
            </li>
        @endfor
        <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
            <a href="{{ $paginator->url($paginator->currentPage()+1) }}" >Next</a>
        </li>
    </ul>
@endif

