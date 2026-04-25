@props(['type' => 'success'])

@php
$styles = [
    'success' => ['bg' => 'rgba(22,163,74,0.12)',  'border' => 'rgba(22,163,74,0.25)',  'text' => '#15803d', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z'],
    'error'   => ['bg' => 'rgba(190,8,34,0.10)',   'border' => 'rgba(190,8,34,0.25)',   'text' => '#BE0822', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z'],
    'warning' => ['bg' => 'rgba(234,179,8,0.12)',  'border' => 'rgba(234,179,8,0.30)',  'text' => '#92400e', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
];
$s = $styles[$type] ?? $styles['success'];
@endphp

<div class="flex items-start gap-3 px-4 py-3 rounded-xl mb-4 text-sm font-medium"
     style="background:{{ $s['bg'] }};border:1px solid {{ $s['border'] }};color:{{ $s['text'] }};">
    <svg class="flex-shrink-0 mt-0.5" width="16" height="16" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2">
        <path d="{{ $s['icon'] }}"/>
    </svg>
    <span>{{ $slot }}</span>
</div>
