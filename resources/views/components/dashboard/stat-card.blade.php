@props([
    'label',
    'value',
    'hint' => null,
    'color' => 'slate',
])

@php
    $colors = [
        'slate' => 'bg-slate-900 text-white',
        'blue' => 'bg-blue-600 text-white',
        'amber' => 'bg-amber-500 text-white',
        'emerald' => 'bg-emerald-600 text-white',
        'indigo' => 'bg-indigo-600 text-white',
    ];
    $colorClass = $colors[$color] ?? $colors['slate'];
@endphp

<div class="overflow-hidden rounded-xl shadow-sm {{ $colorClass }}">
    <div class="p-5">
        <p class="text-xs font-semibold uppercase tracking-wider opacity-80">{{ $label }}</p>
        <p class="mt-2 text-3xl font-bold">{{ $value }}</p>
        @if ($hint)
            <p class="mt-2 text-xs opacity-80">{{ $hint }}</p>
        @endif
    </div>
</div>
