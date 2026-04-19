<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-10">
    <div class="font-semibold text-slate-800">
        {{ 'Evaluasi Kurikulum' }}
    </div>

    <div class="flex items-center space-x-4">
        <button id="profileBtn" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
            <img src="{{ asset('profile/user-11.png') }}" alt="Profile" class="h-10 w-10 rounded-full cursor-pointer">
            <span class="text-sm font-medium text-slate-600">{{ Auth::user()->name ?? 'Administrator' }}</span>
        </button>
    </div>
</header>

<!-- Profile Popup Modal -->
<div id="profileModal"
    class="hidden fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    onclick="if(event.target === this) closeProfileModal()">
    <div class="bg-white rounded-xl shadow-lg max-w-sm w-full" onclick="event.stopPropagation()">
        <div class="p-8 text-center">
            <!-- Close Button -->
            <button onclick="closeProfileModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <!-- Profile Photo -->
            <img src="{{ asset('profile/user-11.png') }}" alt="Profile"
                class="h-24 w-24 rounded-full mx-auto mb-4 object-cover">

            <!-- User Name -->
            <h3 class="text-xl font-semibold text-slate-900 mb-1">{{ Auth::user()->name ?? 'Administrator' }}</h3>
            <p class="text-sm text-slate-600 mb-6">{{ Auth::user()->email ?? '-' }}</p>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit"
                    class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors focus:outline-none">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openProfileModal() {
        document.getElementById('profileModal').classList.remove('hidden');
    }

    function closeProfileModal() {
        document.getElementById('profileModal').classList.add('hidden');
    }

    // Open profile modal on click
    document.getElementById('profileBtn').addEventListener('click', openProfileModal);

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeProfileModal();
        }
    });
</script>
