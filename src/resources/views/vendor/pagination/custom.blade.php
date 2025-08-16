@if ($paginator->hasPages())
    <nav class="pagination-nav" role="navigation" aria-label="Pagination Navigation">
        <ul class="pagination-list">
            {{-- Previous Page Link（常に有効） --}}
            <li class="pagination-item">
                <a href="{{ $paginator->previousPageUrl() ?? '#' }}" rel="prev" aria-label="@lang('pagination.previous')">&lt;</a>
            </li>

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="pagination-item disabled"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination-item active"><span>{{ $page }}</span></li>
                        @else
                            <li class="pagination-item"><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link（常に有効） --}}
            <li class="pagination-item">
                <a href="{{ $paginator->nextPageUrl() ?? '#' }}" rel="next" aria-label="@lang('pagination.next')">&gt;</a>
            </li>
        </ul>
    </nav>
@endif
