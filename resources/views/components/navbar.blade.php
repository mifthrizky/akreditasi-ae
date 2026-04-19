@php
$navigation = config('navigation');
$mainLink = $navigation['main'];
$sections = $navigation['sections'];
$icons = $navigation['icons'];
$currentRoute = request()->path();
@endphp

<nav class="flex-1 overflow-y-auto py-4 space-y-1 px-3">
    {{-- Main Dashboard Link --}}
    <a href="{{ $mainLink['route'] }}"
        class="flex items-center px-3 py-2.5 @if(str_contains($currentRoute, 'dashboard')) bg-blue-600 text-white text-white @else text-slate-300 hover:bg-slate-800 hover:text-white @endif rounded-lg font-medium transition-colors">
        {!! $icons[$mainLink['icon']] !!}
        {{ $mainLink['label'] }}
    </a>

    {{-- Menu Sections --}}
    @foreach($sections as $section)
    <div class="pt-4 pb-2">
        <p class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $section['title'] }}</p>
    </div>

    @foreach($section['items'] as $item)
    @php
    $isActive = str_starts_with($currentRoute, ltrim($item['route'], '/'));
    @endphp
    <a href="{{ $item['route'] }}"
        class="flex items-center px-3 py-2.5 @if($isActive) bg-blue-600 @else text-slate-300 hover:bg-slate-800 hover:text-white @endif rounded-lg transition-colors">
        {!! $icons[$item['icon']] !!}
        {{ $item['label'] }}
    </a>
    @endforeach
    @endforeach
</nav>