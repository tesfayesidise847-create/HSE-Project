<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EEC HSE') }} — HSE Management</title>
        <meta name="description" content="Ethiopian Engineering Corporation — HSE Material Management System">

<<<<<<< HEAD
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

        <!-- Theme (prevent FOUC) -->
=======
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
        <script>
            if (localStorage.getItem('theme') === 'dark' || (! localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<<<<<<< HEAD
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-950">

        @php
            /* ─────────────────────────────────────────────────────────────
               Sidebar helper closures
            ───────────────────────────────────────────────────────────── */
            $sidebarLink = function (string $href, string $label, bool $active, string $iconPath): string {
                if ($active) {
                    $classes = 'sidebar-link-active text-cyan-400 dark:text-cyan-300';
                } else {
                    $classes = 'text-slate-300 hover:bg-white/8 hover:text-white dark:text-slate-400 dark:hover:text-white';
                }
                return sprintf(
                    '<a href="%s" title="%s" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150 %s"><svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">%s</svg><span x-show="! sidebarCollapsed" x-cloak class="truncate">%s</span></a>',
                    e($href), e($label), e($classes), $iconPath, e($label),
                );
            };

            $sidebarParent = function (string $label, string $iconPath, string $openVar): string {
                return sprintf(
                    '<button type="button" @click="%s = !%s" class="flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 hover:bg-white/8 hover:text-white dark:text-slate-400 dark:hover:text-white transition-all duration-150"><div class="flex items-center gap-3"><svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">%s</svg><span x-show="! sidebarCollapsed" x-cloak class="truncate">%s</span></div><svg x-show="! sidebarCollapsed" x-cloak class="h-4 w-4 shrink-0 transition-transform duration-200 text-slate-400" :class="%s ? \'rotate-180\' : \'\'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg></button>',
                    $openVar, $openVar, $iconPath, e($label), $openVar,
                );
            };

            $sidebarSubLink = function (string $href, string $label, bool $active): string {
                $classes = $active
                    ? 'text-cyan-400 dark:text-cyan-300 font-semibold'
                    : 'text-slate-400 hover:text-white dark:text-slate-500 dark:hover:text-white';
                return sprintf(
                    '<a href="%s" class="flex items-center gap-2 rounded-lg py-2 text-xs font-medium transition-all duration-150 %s" :class="sidebarCollapsed ? \'pl-3\' : \'pl-11\'"><span class="h-1.5 w-1.5 rounded-full shrink-0 %s"></span><span x-show="! sidebarCollapsed" x-cloak>%s</span></a>',
                    e($href), $classes, $active ? 'bg-cyan-400' : 'bg-slate-600', e($label),
                );
            };

            $icons = [
                'dashboard' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12 12 4.5 20.25 12M5.25 10.5V19.5h4.5v-5.25h4.5V19.5h4.5V10.5" />',
                'users'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0M18 8.25a2.625 2.625 0 0 1 0 5.25M21 20.25a5.25 5.25 0 0 0-3.75-5.03" />',
                'roles'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M12 3.75l7.5 3v5.25c0 4.5-3 7.5-7.5 8.25-4.5-.75-7.5-3.75-7.5-8.25V6.75l7.5-3Z" />',
                'projects'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M5.25 6.75v12h13.5v-12M8.25 10.5h7.5M8.25 14.25h4.5" />',
                'assign'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h9M7.5 12h9M7.5 16.5h4.5M4.5 4.5h15v15h-15z" />',
                'report'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3.75h7.5L18.75 8.25v12h-12V3.75ZM14.25 3.75v4.5h4.5M9 13.5h6M9 16.5h6M9 10.5h2.25" />',
                'employees' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 12a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5ZM4.5 20.25a7.5 7.5 0 0 1 15 0M18.75 4.5v4.5M21 6.75h-4.5" />',
                'materials' => '<path stroke-linecap="round" stroke-linejoin="round" d="m12 3.75 7.5 4.125v8.25L12 20.25l-7.5-4.125v-8.25L12 3.75ZM4.5 7.875 12 12l7.5-4.125M12 20.25V12" />',
                'quantity'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12M6 12h12M4.5 4.5h15v15h-15z" />',
            ];
        @endphp

        <div class="min-h-screen">
=======
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465

            {{-- ═══════════════════════════════════════════════════════ --}}
            {{-- TOP NAV BAR                                            --}}
            {{-- ═══════════════════════════════════════════════════════ --}}
            <header class="glass-dark sticky top-0 z-40 flex h-16 items-center justify-between px-4 sm:px-6">

                {{-- Left: Logo + Brand --}}
                <div class="flex items-center gap-3">
                    {{-- Mobile hamburger (Alpine opens mobile sidebar) --}}
                    <button
                        type="button"
                        @click="mobileMenuOpen = ! mobileMenuOpen"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-300 hover:bg-white/10 hover:text-white focus:outline-none lg:hidden"
                        aria-label="{{ __('Open menu') }}"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                        <x-application-logo class="h-9 w-auto object-contain" />
                        <div class="hidden sm:block">
                            <p class="text-xs font-bold tracking-wider text-cyan-400 uppercase leading-none">EEC</p>
                            <p class="text-[10px] text-slate-400 leading-none mt-0.5">HSE Management</p>
                        </div>
                    </a>

                    {{-- Gradient accent separator --}}
                    <div class="hidden lg:block h-8 w-px bg-gradient-to-b from-transparent via-cyan-500/30 to-transparent mx-2"></div>

                    {{-- Page title (from $header slot, shown in topbar context) --}}
                    @isset($header)
                        <div class="hidden lg:block text-sm font-medium text-slate-300">
                            {{ strip_tags((string) $header) }}
                        </div>
                    @endisset
                </div>

                {{-- Right: Actions --}}
                <div class="flex items-center gap-1.5 sm:gap-2">

                    {{-- Notification Bell --}}
                    <x-notification-bell />

                    {{-- Theme Toggle --}}
                    <div x-data="themeSwitcher()">
                        <button
                            type="button"
                            @click="toggleTheme()"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-300 hover:bg-white/10 hover:text-white transition-all focus:outline-none focus:ring-2 focus:ring-cyan-500/50"
                            :aria-label="isDark ? '{{ __('Switch to light mode') }}' : '{{ __('Switch to night mode') }}'"
                        >
                            <svg x-show="! isDark" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21m8.25-9h-2.25M6 12H3.75m14.03-5.78-1.59 1.59M7.81 16.19l-1.59 1.59m11.56 0-1.59-1.59M7.81 7.81 6.22 6.22M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                            </svg>
                            <svg x-show="isDark" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
                            </svg>
                        </button>
                    </div>

                    {{-- User Avatar Dropdown --}}
                    <x-dropdown align="right" width="52">
                        <x-slot name="trigger">
                            <button
                                id="user-menu-button"
                                class="flex items-center gap-2 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-all focus:outline-none focus:ring-2 focus:ring-cyan-500/50"
                            >
                                {{-- Avatar initials --}}
                                <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-cyan-500 to-teal-600 text-xs font-bold text-white shadow-eec">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span class="hidden sm:block max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Signed in as') }}</p>
                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <x-dropdown-link :href="route('profile.edit')">
                                <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </header>
            {{-- /TOP NAV --}}

            {{-- ═══════════════════════════════════════════════════════ --}}
            {{-- BODY: SIDEBAR + CONTENT                                --}}
            {{-- ═══════════════════════════════════════════════════════ --}}
            <div
<<<<<<< HEAD
                x-data="{
                    sidebarCollapsed: localStorage.getItem('sidebar-collapsed') === 'true',
                    mobileMenuOpen: false,
                    toggleSidebar() {
                        this.sidebarCollapsed = ! this.sidebarCollapsed;
                        localStorage.setItem('sidebar-collapsed', this.sidebarCollapsed ? 'true' : 'false');
                    },
                    openMaterials: {{ request()->routeIs('materials.*', 'material-histories.*') ? 'true' : 'false' }},
                    openProjects:  {{ (request()->routeIs('projects.*') || request()->routeIs('site-officer.projects.*')) ? 'true' : 'false' }},
                    openAssign:    {{ (request()->routeIs('material-assignments.*') || request()->routeIs('site-officer.employee-assignments.create') || request()->routeIs('site-officer.material-requests.*')) ? 'true' : 'false' }},
                    openReport:    {{ (request()->routeIs('material-reports.*', 'site-officer.material-reports.*', 'site-officer.employee-assignments.index')) ? 'true' : 'false' }},
                    openUserManagement: {{ (request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('employees.*')) ? 'true' : 'false' }}
                }"
                class="flex min-h-[calc(100vh-4rem)]"
            >

                {{-- ── SIDEBAR OVERLAY (mobile) ─────────────────────── --}}
                <div
                    x-show="mobileMenuOpen"
                    x-cloak
                    @click="mobileMenuOpen = false"
                    class="fixed inset-0 z-30 bg-black/60 lg:hidden"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                ></div>

                {{-- ── SIDEBAR ──────────────────────────────────────── --}}
                <aside
                    class="fixed top-16 bottom-0 z-30 flex flex-col overflow-y-auto transition-all duration-300 ease-in-out
                           bg-[#0b1e2d] border-r border-cyan-900/40
                           lg:sticky lg:z-auto"
                    :class="{
                        'w-64':  (! sidebarCollapsed),
                        'w-20':  sidebarCollapsed,
                        '-translate-x-full lg:translate-x-0': ! mobileMenuOpen,
                        'translate-x-0':  mobileMenuOpen,
                        'w-64':  mobileMenuOpen
                    }"
                >
                    {{-- Sidebar top gradient stripe --}}
                    <div class="h-0.5 w-full eec-gradient-animated shrink-0"></div>

                    {{-- Collapse toggle (desktop only) --}}
                    <div class="flex items-center justify-end px-3 pt-4 pb-2 shrink-0">
                        <button
                            type="button"
                            @click="toggleSidebar()"
                            class="hidden h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/40 lg:inline-flex transition-all"
                            :aria-label="sidebarCollapsed ? '{{ __('Expand sidebar') }}' : '{{ __('Collapse sidebar') }}'"
                        >
                            <svg class="h-4 w-4 transition-transform duration-300" :class="{ 'rotate-180': ! sidebarCollapsed }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 9l-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    </div>

                    {{-- Section label --}}
                    <div x-show="! sidebarCollapsed" x-cloak class="px-4 pb-2">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-cyan-600/70">Navigation</p>
                    </div>

                    <nav class="flex-1 space-y-0.5 px-3 pb-6">

                        {!! $sidebarLink(route('dashboard'), __('Dashboard'), request()->routeIs('dashboard'), $icons['dashboard']) !!}

                        {{-- Materials --}}
                        @hasanyrole('Admin|HSE Officer')
                            <div class="space-y-0.5">
                                {!! $sidebarParent(__('Materials'), $icons['materials'], 'openMaterials') !!}
                                <div x-show="openMaterials" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-0.5">
                                    @hasanyrole('Admin|HSE Officer')
                                        {!! $sidebarSubLink(route('materials.index'), __('Manage Material'), request()->routeIs('materials.*')) !!}
                                    @endhasanyrole
                                    @role('HSE Officer')
                                        {!! $sidebarSubLink(route('material-quantities.index'), __('Add Quantity'), request()->routeIs('material-quantities.*')) !!}
                                        {!! $sidebarSubLink(route('material-histories.index'), __('Material History'), request()->routeIs('material-histories.*')) !!}
                                    @endrole
                                </div>
                            </div>
                        @endhasanyrole

                        {{-- Projects --}}
                        @hasanyrole('Admin|HSE Officer|HSE Site Officer')
                            <div class="space-y-0.5">
                                {!! $sidebarParent(__('Projects'), $icons['projects'], 'openProjects') !!}
                                <div x-show="openProjects" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-0.5">
                                    @role('HSE Site Officer')
                                        {!! $sidebarSubLink(route('site-officer.projects.index'), __('My Projects'), request()->routeIs('site-officer.projects.index') || request()->routeIs('site-officer.projects.show')) !!}
                                        {!! $sidebarSubLink(route('site-officer.projects.index'), __('Manage Employees'), request()->routeIs('site-officer.projects.employees.*')) !!}
                                    @endrole
                                    @hasanyrole('Admin|HSE Officer')
                                        {!! $sidebarSubLink(route('projects.index'), __('Projects'), request()->routeIs('projects.*')) !!}
                                    @endhasanyrole
                                </div>
                            </div>
                        @endhasanyrole

                        {{-- Assign Material --}}
                        @hasanyrole('HSE Site Officer|HSE Officer')
                            <div class="space-y-0.5">
                                {!! $sidebarParent(__('Assign Material'), $icons['assign'], 'openAssign') !!}
                                <div x-show="openAssign" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-0.5">
                                    @role('HSE Site Officer')
                                        {!! $sidebarSubLink(route('site-officer.material-requests.create'), __('Request Material'), request()->routeIs('site-officer.material-requests.*')) !!}
                                        {!! $sidebarSubLink(route('site-officer.employee-assignments.create'), __('Assign to employee'), request()->routeIs('site-officer.employee-assignments.create')) !!}
                                    @endrole
                                    @role('HSE Officer')
                                        {!! $sidebarSubLink(route('hse-officer.material-requests.index'), __('Material Requests'), request()->routeIs('hse-officer.material-requests.*')) !!}
                                        {!! $sidebarSubLink(route('material-assignments.create'), __('Assign to Project'), request()->routeIs('material-assignments.*')) !!}
                                    @endrole
                                </div>
                            </div>
                        @endhasanyrole

                        {{-- Report --}}
                        @hasanyrole('HSE Site Officer|HSE Officer')
                            <div class="space-y-0.5">
                                {!! $sidebarParent(__('Report'), $icons['report'], 'openReport') !!}
                                <div x-show="openReport" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-0.5">
                                    @role('HSE Officer')
                                        {!! $sidebarSubLink(route('material-reports.head-office'), __('Head Office Report'), request()->routeIs('material-reports.head-office')) !!}
                                        {!! $sidebarSubLink(route('material-reports.site'), __('Site Material Reports'), request()->routeIs('material-reports.site')) !!}
                                    @endrole
                                    @hasanyrole('HSE Site Officer|HSE Officer')
                                        {!! $sidebarSubLink(route('site-officer.employee-assignments.index'), __('Employee Assignment History'), request()->routeIs('site-officer.employee-assignments.index')) !!}
                                    @endhasanyrole
                                </div>
                            </div>
                        @endhasanyrole

                        {{-- User Management --}}
                        @hasanyrole('Admin|HSE Officer')
                            <div class="mt-4 mb-2" x-show="! sidebarCollapsed" x-cloak>
                                <p class="px-3 text-[10px] font-semibold uppercase tracking-widest text-cyan-600/70">Administration</p>
                            </div>
                            <div class="space-y-0.5">
                                {!! $sidebarParent(__('User Management'), $icons['users'], 'openUserManagement') !!}
                                <div x-show="openUserManagement" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-0.5 mt-0.5">
                                    @hasanyrole('Admin|HSE Officer')
                                        {!! $sidebarSubLink(route('users.index'), __('Users'), request()->routeIs('users.*')) !!}
                                    @endhasanyrole
                                    @role('Admin')
                                        {!! $sidebarSubLink(route('employees.index'), __('Employees'), request()->routeIs('employees.*')) !!}
                                        {!! $sidebarSubLink(route('roles.index'), __('Roles'), request()->routeIs('roles.*')) !!}
                                    @endrole
                                </div>
                            </div>
                        @endhasanyrole

                    </nav>

                    {{-- Sidebar footer: user info --}}
                    <div
                        x-show="! sidebarCollapsed"
                        x-cloak
                        class="shrink-0 border-t border-cyan-900/40 px-4 py-4"
                    >
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-cyan-500 to-teal-600 text-xs font-bold text-white shadow-eec">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-xs font-semibold text-slate-200">{{ Auth::user()->name }}</p>
                                <p class="truncate text-[10px] text-slate-500">
                                    {{ Auth::user()->roles->first()?->name ?? __('No role') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </aside>
                {{-- /SIDEBAR --}}

                {{-- ── MAIN CONTENT ─────────────────────────────────── --}}
                <div class="flex-1 min-w-0 flex flex-col">

                    {{-- Page header bar --}}
                    @isset($header)
                        <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-800 dark:bg-gray-900/50">
                            <div class="flex items-center gap-3">
                                <div class="h-6 w-1 rounded-full eec-gradient shrink-0"></div>
                                <div>{{ $header }}</div>
                            </div>
                        </div>
                    @endisset

                    {{-- Flash messages --}}
                    @if (session('success'))
                        <div
                            x-data="{ show: true }"
                            x-show="show"
                            x-init="setTimeout(() => show = false, 5000)"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="mx-6 mt-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 dark:border-emerald-800 dark:bg-emerald-900/20 animate-slide-in-up"
                        >
                            <svg class="h-5 w-5 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">{{ session('success') }}</p>
                            <button @click="show = false" class="ml-auto text-emerald-500 hover:text-emerald-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
=======
                x-data="sidebarState()"
                x-init="initSidebar()"
                class="flex flex-col lg:flex-row"
            >
                {{-- Sidebar Overlay (mobile) --}}
                <div
                    x-show="mobileOpen"
                    x-cloak
                    @click="mobileOpen = false"
                    class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"
                ></div>

                <aside
                    class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col border-r border-cyan-200 bg-gradient-to-b from-cyan-50 to-white shadow-xl transition-all duration-300 dark:border-cyan-900 dark:from-cyan-950 dark:to-gray-900 lg:static lg:z-auto lg:translate-x-0"
                    :class="{
                        'translate-x-0': mobileOpen,
                        '-translate-x-full': !mobileOpen,
                        'lg:w-20': sidebarCollapsed,
                        'lg:w-64': !sidebarCollapsed
                    }"
                >
                    {{-- Sidebar Header --}}
                    <div class="flex items-center justify-between border-b border-cyan-200 px-4 py-4 dark:border-cyan-900 lg:px-4">
                        <div x-show="!sidebarCollapsed" x-cloak class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-600 text-white dark:bg-cyan-500 dark:text-slate-950">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-cyan-800 dark:text-cyan-200">HSE System</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <button
                                type="button"
                                @click="toggleSidebar()"
                                class="hidden h-8 w-8 items-center justify-center rounded-lg text-cyan-600 hover:bg-cyan-100 hover:text-cyan-800 focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-cyan-400 dark:hover:bg-cyan-900 dark:hover:text-white lg:inline-flex"
                                :aria-label="sidebarCollapsed ? '{{ __('Expand sidebar') }}' : '{{ __('Collapse sidebar') }}'"
                            >
                                <svg class="h-4 w-4 transition-transform duration-300" :class="{ 'rotate-180': sidebarCollapsed }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h15M4.5 12h15M4.5 17.25h15M9.75 8.25 6 12l3.75 3.75" />
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
                                </svg>
                            </button>
                            <button
                                type="button"
                                @click="mobileOpen = false"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-cyan-600 hover:bg-cyan-100 dark:text-cyan-400 dark:hover:bg-cyan-900 lg:hidden"
                            >
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
<<<<<<< HEAD
                    @endif

                    @if (session('error'))
                        <div
                            x-data="{ show: true }"
                            x-show="show"
                            x-init="setTimeout(() => show = false, 6000)"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="mx-6 mt-4 flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 dark:border-rose-800 dark:bg-rose-900/20 animate-slide-in-up"
                        >
                            <svg class="h-5 w-5 shrink-0 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/>
                            </svg>
                            <p class="text-sm font-medium text-rose-800 dark:text-rose-300">{{ session('error') }}</p>
                            <button @click="show = false" class="ml-auto text-rose-500 hover:text-rose-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
=======
                    </div>

                    {{-- Sidebar Content --}}
                    <div class="flex-1 overflow-y-auto px-3 py-4 scrollbar-thin scrollbar-thumb-cyan-200 dark:scrollbar-thumb-cyan-800">
                        <nav class="space-y-1.5">
                            {{-- Dashboard --}}
                            <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                                icon="M3.75 12 12 4.5 20.25 12M5.25 10.5V19.5h4.5v-5.25h4.5V19.5h4.5V10.5"
                                :label="__('Dashboard')" />

                            {{-- Materials --}}
                            @hasanyrole('Admin|HSE Officer')
                                <x-sidebar-group label="Materials" :open="request()->routeIs('materials.*', 'material-histories.*')"
                                    icon="m12 3.75 7.5 4.125v8.25L12 20.25l-7.5-4.125v-8.25L12 3.75ZM4.5 7.875 12 12l7.5-4.125M12 20.25V12">
                                    @hasanyrole('Admin|HSE Officer')
                                        <x-sidebar-sub-link :href="route('materials.index')" :active="request()->routeIs('materials.*')">
                                            {{ __('Manage Material') }}
                                        </x-sidebar-sub-link>
                                    @endhasanyrole
                                    @role('HSE Officer')
                                        <x-sidebar-sub-link :href="route('material-quantities.index')" :active="request()->routeIs('material-quantities.*')">
                                            {{ __('Add Quantity') }}
                                        </x-sidebar-sub-link>
                                        <x-sidebar-sub-link :href="route('material-histories.index')" :active="request()->routeIs('material-histories.*')">
                                            {{ __('Material History') }}
                                        </x-sidebar-sub-link>
                                    @endrole
                                </x-sidebar-group>
                            @endhasanyrole

                            {{-- Projects --}}
                            @hasanyrole('Admin|HSE Officer|HSE Site Officer')
                                <x-sidebar-group label="Projects" :open="request()->routeIs('projects.*', 'site-officer.projects.*')"
                                    icon="M3.75 6.75h16.5M5.25 6.75v12h13.5v-12M8.25 10.5h7.5M8.25 14.25h4.5">
                                    @role('HSE Site Officer')
                                        <x-sidebar-sub-link :href="route('site-officer.projects.index')" :active="request()->routeIs('site-officer.projects.index') || request()->routeIs('site-officer.projects.show')">
                                            {{ __('My Projects') }}
                                        </x-sidebar-sub-link>
                                        <x-sidebar-sub-link :href="route('site-officer.projects.employees.index', ['project' => '__placeholder__'])" :active="request()->routeIs('site-officer.projects.employees.*')">
                                            {{ __('Manage Employees') }}
                                        </x-sidebar-sub-link>
                                    @endrole
                                    @hasanyrole('Admin|HSE Officer')
                                        <x-sidebar-sub-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
                                            {{ __('Projects') }}
                                        </x-sidebar-sub-link>
                                    @endhasanyrole
                                </x-sidebar-group>
                            @endhasanyrole

                            {{-- Assign / Request Material --}}
                            @hasanyrole('HSE Site Officer|HSE Officer')
                                <x-sidebar-group label="Assign Material" :open="request()->routeIs('material-assignments.*', 'site-officer.employee-assignments.*', 'site-officer.material-requests.*', 'hse-officer.material-requests.*')"
                                    icon="M7.5 7.5h9M7.5 12h9M7.5 16.5h4.5M4.5 4.5h15v15h-15z">
                                    @role('HSE Site Officer')
                                        <x-sidebar-sub-link :href="route('site-officer.material-requests.create')" :active="request()->routeIs('site-officer.material-requests.*')">
                                            {{ __('Request Material') }}
                                        </x-sidebar-sub-link>
                                        <x-sidebar-sub-link :href="route('site-officer.employee-assignments.create')" :active="request()->routeIs('site-officer.employee-assignments.create')">
                                            {{ __('Assign to Employees') }}
                                        </x-sidebar-sub-link>
                                    @endrole
                                    @role('HSE Officer')
                                        <x-sidebar-sub-link :href="route('hse-officer.material-requests.index')" :active="request()->routeIs('hse-officer.material-requests.*')">
                                            {{ __('Material Requests') }}
                                        </x-sidebar-sub-link>
                                        <x-sidebar-sub-link :href="route('material-assignments.create')" :active="request()->routeIs('material-assignments.*')">
                                            {{ __('Assign to Project') }}
                                        </x-sidebar-sub-link>
                                    @endrole
                                </x-sidebar-group>
                            @endhasanyrole

                            {{-- Reports --}}
                            @hasanyrole('HSE Site Officer|HSE Officer')
                                <x-sidebar-group label="Reports" :open="request()->routeIs('material-reports.*', 'site-officer.material-reports.*', 'site-officer.employee-assignments.index')"
                                    icon="M6.75 3.75h7.5L18.75 8.25v12h-12V3.75ZM14.25 3.75v4.5h4.5M9 13.5h6M9 16.5h6M9 10.5h2.25">
                                    @role('HSE Officer')
                                        <x-sidebar-sub-link :href="route('material-reports.head-office')" :active="request()->routeIs('material-reports.head-office')">
                                            {{ __('Head Office Report') }}
                                        </x-sidebar-sub-link>
                                        <x-sidebar-sub-link :href="route('material-reports.site')" :active="request()->routeIs('material-reports.site')">
                                            {{ __('Site Reports') }}
                                        </x-sidebar-sub-link>
                                    @endrole
                                    @hasanyrole('HSE Site Officer|HSE Officer')
                                        <x-sidebar-sub-link :href="route('site-officer.employee-assignments.index')" :active="request()->routeIs('site-officer.employee-assignments.index')">
                                            {{ __('Employee History') }}
                                        </x-sidebar-sub-link>
                                    @endhasanyrole
                                </x-sidebar-group>
                            @endhasanyrole

                            {{-- User Management --}}
                            @hasanyrole('Admin|HSE Officer')
                                <x-sidebar-group label="User Management" :open="request()->routeIs('users.*', 'roles.*', 'employees.*')"
                                    icon="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0M18 8.25a2.625 2.625 0 0 1 0 5.25M21 20.25a5.25 5.25 0 0 0-3.75-5.03">
                                    @hasanyrole('Admin|HSE Officer')
                                        <x-sidebar-sub-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                                            {{ __('Users') }}
                                        </x-sidebar-sub-link>
                                    @endhasanyrole
                                    @role('Admin')
                                        <x-sidebar-sub-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">
                                            {{ __('Employees') }}
                                        </x-sidebar-sub-link>
                                        <x-sidebar-sub-link :href="route('roles.index')" :active="request()->routeIs('roles.*')">
                                            {{ __('Roles') }}
                                        </x-sidebar-sub-link>
                                    @endrole
                                </x-sidebar-group>
                            @endhasanyrole
                        </nav>
                    </div>
                </aside>

                {{-- Main Content --}}
                <div class="flex min-h-screen flex-1 flex-col lg:min-h-0">
                    {{-- Mobile Menu Toggle --}}
                    <div class="sticky top-0 z-20 flex items-center gap-3 border-b border-gray-200 bg-white px-4 py-3 shadow-sm dark:border-gray-700 dark:bg-gray-800 lg:hidden">
                        <button
                            type="button"
                            @click="mobileOpen = true"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                        >
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">HSE System</span>
                    </div>

                    @isset($header)
                        <header class="border-b border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                            <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="flex-1">
                        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                            {{ $slot }}
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
                        </div>
                    @endif

                    <main class="flex-1 p-6 lg:p-8">
                        {{ $slot }}
                    </main>
                </div>
                {{-- /MAIN CONTENT --}}

            </div>
        </div>

<<<<<<< HEAD
=======
        <script>
            function sidebarState() {
                return {
                    mobileOpen: false,
                    sidebarCollapsed: false,
                    initSidebar() {
                        this.sidebarCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
                    },
                    toggleSidebar() {
                        this.sidebarCollapsed = !this.sidebarCollapsed;
                        localStorage.setItem('sidebar-collapsed', this.sidebarCollapsed ? 'true' : 'false');
                    }
                };
            }
        </script>
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
    </body>
</html>