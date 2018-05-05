@if ($paginator->hasPages())
    <nav class="pagination is-centered" role="navigation" aria-label="pagination">
        <a href="{{ $paginator->previousPageUrl() }}" class="pagination-previous" rel="prev"{{ $paginator->onFirstPage() ? ' disabled aria-disabled="true"': '' }}>
            @lang('pagination.previous')
        </a>

        <a href="{{ $paginator->nextPageUrl() }}" class="pagination-next" rel="next"{{ $paginator->hasMorePages() ? '' : ' disabled aria-disabled="true"' }}>
            @lang('pagination.next')
        </a>
    </nav>
@endif
