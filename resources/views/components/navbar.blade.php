@php
    $navigation = config('navigation');
    $mainLink = $navigation['main'];
    $sections = $navigation['sections'];
    $icons = $navigation['icons'];
    $currentRouteName = Route::currentRouteName();
    $userRole = Auth::user()->role;

    // Helper function to check if user can access a route
    $canAccess = function ($routeName) use ($userRole) {
        $permission = \App\Models\PagePermission::where('route_name', $routeName)->first();
        if (!$permission) {
            // If no permission defined, allow admin only
            return $userRole === 'admin';
        }
        return $permission->hasRole($userRole);
    };
@endphp

<nav class="flex-1 overflow-y-auto py-4 space-y-1 px-3">
    {{-- Main Dashboard Link --}}
    @if ($canAccess($mainLink['route']))
        @php
            $dashboardRoute = $mainLink['route'] === 'dashboard' ? $userRole . '.dashboard' : $mainLink['route'];
        @endphp
        <a href="{{ route($dashboardRoute) }}"
            class="flex items-center px-3 py-2.5 @if (str_ends_with($currentRouteName, '.dashboard')) bg-blue-600 text-white @else text-slate-300 hover:bg-slate-800 hover:text-white @endif rounded-lg font-medium transition-colors">
            {!! $icons[$mainLink['icon']] !!}
            {{ $mainLink['label'] }}
        </a>
    @endif

    {{-- Menu Sections --}}
    @foreach ($sections as $section)
        @php
            // Check if any items in this section are accessible
            $hasAccessibleItems = false;
            foreach ($section['items'] as $item) {
                if ($canAccess($item['route'])) {
                    $hasAccessibleItems = true;
                    break;
                }
            }
        @endphp

        @if ($hasAccessibleItems)
            <div class="pt-4 pb-2">
                <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $section['title'] }}</p>
            </div>

            @foreach ($section['items'] as $item)
                @if ($canAccess($item['route']))
                    @php
                        $routeBase = substr($item['route'], 0, strrpos($item['route'], '.'));
                        $isActive = str_starts_with($currentRouteName, $routeBase);
                    @endphp
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center px-3 py-2.5 @if ($isActive) bg-blue-600 text-white @else text-slate-300 hover:bg-slate-800 hover:text-white @endif rounded-lg transition-colors">
                        {!! $icons[$item['icon']] !!}
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        @endif
    @endforeach
</nav>
