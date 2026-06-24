@props(['href' => '', 'active' => false])

@php
$classes = $active
    ? 'bg-cyan-500/15 text-cyan-700 dark:text-cyan-300 font-semibold shadow-inner border-l-2 border-cyan-500 dark:border-cyan-400'
    : 'text-cyan-900 hover:bg-cyan-100/50 hover:text-cyan-950 dark:text-cyan-200 dark:hover:bg-cyan-900/50 dark:hover:text-white border-l-2 border-transparent';
@endphp

<a href="{{ $href }}" class="flex items-center gap-3 rounded-md py-2 text-xs font-medium transition-all duration-200 {{ $classes }}"
    :class="sidebarCollapsed ? 'px-2 justify-center' : 'px-3'">
    {{ $slot }}
</a>