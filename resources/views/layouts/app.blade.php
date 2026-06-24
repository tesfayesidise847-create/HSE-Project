<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script>
            if (localStorage.getItem('theme') === 'dark' || (! localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <div
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
                        </div>
                    </main>
                </div>
            </div>
        </div>

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
    </body>
</html>