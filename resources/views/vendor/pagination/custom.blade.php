@if ($paginator->hasPages())
<nav style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
    <p style="font-size: 0.875rem; color: var(--text-muted);">
        Menampilkan
        <span style="color: var(--text-main); font-weight: 600;">{{ $paginator->firstItem() }}</span>
        &ndash;
        <span style="color: var(--text-main); font-weight: 600;">{{ $paginator->lastItem() }}</span>
        dari
        <span style="color: var(--text-main); font-weight: 600;">{{ $paginator->total() }}</span>
        data
    </p>

    <div style="display: flex; align-items: center; gap: 0.375rem;">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span style="padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--glass-border); color: var(--text-muted); opacity: 0.4; font-size: 0.875rem; cursor: not-allowed; user-select: none;">&#8592;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--glass-border); background: var(--glass-bg); color: var(--text-muted); font-size: 0.875rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.15)';this.style.color='#fff'" onmouseout="this.style.background='var(--glass-bg)';this.style.color='var(--text-muted)'">&#8592;</span>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="padding: 0.4rem 0.5rem; color: var(--text-muted); font-size: 0.875rem;">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); background: var(--primary); color: var(--bg-color); font-weight: 700; font-size: 0.875rem; min-width: 2rem; text-align: center;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--glass-border); background: var(--glass-bg); color: var(--text-muted); font-size: 0.875rem; text-decoration: none; min-width: 2rem; text-align: center; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.15)';this.style.color='#fff'" onmouseout="this.style.background='var(--glass-bg)';this.style.color='var(--text-muted)'">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--glass-border); background: var(--glass-bg); color: var(--text-muted); font-size: 0.875rem; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.15)';this.style.color='#fff'" onmouseout="this.style.background='var(--glass-bg)';this.style.color='var(--text-muted)'">&#8594;</span>
        @else
            <span style="padding: 0.4rem 0.75rem; border-radius: var(--radius-sm); border: 1px solid var(--glass-border); color: var(--text-muted); opacity: 0.4; font-size: 0.875rem; cursor: not-allowed; user-select: none;">&#8594;</span>
        @endif
    </div>
</nav>
@endif
