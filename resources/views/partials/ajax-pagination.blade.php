@php
    $hasPaginatorApi = is_object($paginator)
        && method_exists($paginator, 'lastPage')
        && method_exists($paginator, 'currentPage');
@endphp

@if ($hasPaginatorApi && $paginator->lastPage() > 1)
    <div class="ajax-pagination-nav">
        @if ($paginator->onFirstPage())
            <span class="page-btn disabled">Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">Sebelumnya</a>
        @endif

        <span class="page-info">Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">Berikutnya</a>
        @else
            <span class="page-btn disabled">Berikutnya</span>
        @endif
    </div>
@endif

