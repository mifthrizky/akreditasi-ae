<nav class="flex items-center justify-center gap-1">
    <!-- Previous Page Link -->
    @if ($paginator->onFirstPage())
        <span class="px-3 py-2 text-sm font-medium text-slate-400 bg-slate-100 rounded cursor-not-allowed">
            < </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-2 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded transition-colors">
                    < </a>
    @endif

    <!-- Page Numbers -->
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-2 py-1 text-slate-400">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3 py-2 text-sm font-semibold text-white bg-blue-600 rounded">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                        class="px-3 py-2 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endforeach
        @endif
    @endforeach

    <!-- Next Page Link -->
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
            class="px-3 py-2 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded transition-colors">
            > </a>
    @else
        <span class="px-3 py-2 text-sm font-medium text-slate-400 bg-slate-100 rounded cursor-not-allowed">
            >
        </span>
    @endif
</nav>
