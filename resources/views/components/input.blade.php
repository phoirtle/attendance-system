@props([
    'label'       => null,
    'error'       => null,
    'type'        => 'text',
    'id'          => null,
    'placeholder' => '',
])

@php $inputId = $id ?? 'input_' . uniqid(); @endphp

<div class="mb-4">
    @if($label)
    <label for="{{ $inputId }}"
           class="block text-xs font-semibold uppercase tracking-widest mb-1.5"
           style="color: rgba(107,34,50,0.70);">
        {{ $label }}
    </label>
    @endif

    <input
        id="{{ $inputId }}"
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'w-full px-4 py-3 rounded-xl text-sm outline-none transition-all duration-200']) }}
        style="background:rgba(255,255,255,0.55);border:1.5px solid rgba(255,255,255,0.75);color:#3d1a22;font-family:inherit;"
        onfocus="this.style.borderColor='rgba(190,8,34,0.40)';this.style.background='rgba(255,255,255,0.78)';this.style.boxShadow='0 0 0 3px rgba(190,8,34,0.10)'"
        onblur="this.style.borderColor='rgba(255,255,255,0.75)';this.style.background='rgba(255,255,255,0.55)';this.style.boxShadow=''"
    >

    @if($error)
    <p class="mt-1.5 text-xs font-medium" style="color:#BE0822;">{{ $error }}</p>
    @endif
</div>
