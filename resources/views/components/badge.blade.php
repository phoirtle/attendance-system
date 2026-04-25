@props([
    'type' => 'default', // success | warning | danger | default
])

@php
$styles = [
    'success' => 'bg-green-600/14 text-green-700 border border-green-600/25',
    'warning' => 'bg-yellow-500/14 text-yellow-800 border border-yellow-500/25',
    'danger'  => 'bg-[#BE0822]/12 text-[#BE0822] border border-[#BE0822]/22',
    'default' => 'bg-white/40 text-[#3d1a22] border border-white/60',
];
$style = $styles[$type] ?? $styles['default'];
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center gap-1 px-3 py-0.5 rounded-full text-xs font-semibold tracking-wide {$style}"
]) }}>
    {{ $slot }}
</span>
