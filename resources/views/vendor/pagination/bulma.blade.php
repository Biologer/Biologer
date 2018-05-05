@if ($paginator->hasPages())
    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
        <a href="{{ $paginator->previousPageUrl() }}" class="pagination-previous" rel="prev"{{ $paginator->onFirstPage() ? ' disabled aria-disabled="true"': '' }}>
            @lang('pagination.previous')
        </a>

        <a href="{{ $paginator->nextPageUrl() }}" class="pagination-next" rel="next"{{ $paginator->hasMorePages() ? '' : ' disabled aria-disabled="true"' }}>
            @lang('pagination.next')
        </a>

        <ul class="pagination-list">
            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li><span class="pagination-ellipsis" aria-disabled="true">&hellip;</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><a class="pagination-link is-current" aria-label="Page {{ $page }}" aria-current="page">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}" class="pagination-link" aria-label="Goto page {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </ul>
    </nav>
@endif
