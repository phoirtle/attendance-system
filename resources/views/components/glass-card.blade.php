@props([
    'tint'    => 'white',   // white | rose | pink | cream | ivory | ruby | coral
    'padding' => 'p-7',
    'class'   => '',
])

@php
$tints = [
    'white' => 'bg-white/40',
    'rose'  => 'bg-[#E86975]/22',
    'pink'  => 'bg-[#EFAAB0]/30',
    'cream' => 'bg-[#EED7C8]/38',
    'ivory' => 'bg-[#FFF9F5]/55',
    'ruby'  => 'bg-[#BE0822]/18',
    'coral' => 'bg-[#FD9898]/28',
];
$bg = $tints[$tint] ?? $tints['white'];
@endphp

<div {{ $attributes->merge([
    'class' => "relative overflow-hidden rounded-3xl border border-white/50 backdrop-blur-xl shadow-glass {$bg} {$padding} {$class}"
]) }}>
    {{ $slot }}
</div>
