<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-10">
    <div class="font-semibold text-slate-800">
        {{ $title ?? 'Ringkasan Sistem' }}
    </div>

    <div class="flex items-center space-x-4">
        <span class="text-sm font-medium text-slate-600">{{ Auth::user()->name ?? 'Administrator' }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium transition-colors">
                Keluar &rarr;
            </button>
        </form>
    </div>
</header>