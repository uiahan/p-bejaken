{{-- Dipakai lewat $paginator->links('layout.partials.pagination') --}}
@if ($paginator->hasPages())
    <nav class="mt-6 flex items-center justify-between gap-4" aria-label="Halaman">
        <p class="text-sm text-hutan/70">
            Halaman <strong class="font-semibold text-hutan">{{ $paginator->currentPage() }}</strong> dari {{ $paginator->lastPage() }}
        </p>

        <div class="flex gap-2">
            @if ($paginator->onFirstPage())
                <span class="pager is-off" aria-disabled="true">Sebelumnya</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pager">Sebelumnya</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pager">Berikutnya</a>
            @else
                <span class="pager is-off" aria-disabled="true">Berikutnya</span>
            @endif
        </div>
    </nav>
@endif