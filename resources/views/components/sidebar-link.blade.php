@props(['href', 'active' => false, 'icon' => '', 'label' => ''])

@php
$classes = $active
    ? 'bg-cyan-600 text-white shadow-md dark:bg-cyan-500 dark:text-slate-950'
    : 'text-cyan-800 hover:bg-cyan-100 hover:text-cyan-950 dark:text-cyan-200 dark:hover:bg-cyan-900/70 dark:hover:text-white';
@endphp

<a href="{{ $href }}" title="{{ $label }}"
    class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200 {{ $classes }}"
    x-data
    :class="sidebarCollapsed ? 'justify-center px-2' : ''"
>
    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
    </svg>
    <span x-show="!sidebarCollapsed" x-cloak>{{ $label }}</span>
</a>