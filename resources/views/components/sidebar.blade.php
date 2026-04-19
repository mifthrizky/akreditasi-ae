<aside class="w-64 bg-slate-900 text-slate-300 flex flex-col shrink-0 transition-all duration-300">
    <div class="h-16 flex items-center px-6 bg-slate-950 border-b border-slate-800 justify-center">
        <img src="{{ asset('images/polman.png') }}" alt="Polman" class="h-10">
    </div>

    @include('components.navbar')

    <div class="p-4 justify-left">
        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit"
                class="w-full px-4 py-2.5 text-red-500 font-medium rounded-lg transition-colors focus:outline-none flex items-center gap-3 hover:bg-red-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500 shrink-0"
                    viewBox="0 0 512 512"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                    <path fill="currentColor"
                        d="M160 96c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 32C43 32 0 75 0 128L0 384c0 53 43 96 96 96l64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l64 0zM502.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 224 192 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z" />
                </svg>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
