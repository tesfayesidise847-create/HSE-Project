<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <script>
            if (localStorage.getItem('theme') === 'dark' || (! localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        @php
            $sidebarLink = function (string $href, string $label, bool $active, string $iconPath): string {
                $classes = $active
                    ? 'bg-cyan-600 text-white shadow-sm dark:bg-cyan-500 dark:text-slate-950'
                    : 'text-cyan-950 hover:bg-cyan-100 hover:text-cyan-950 dark:text-cyan-100 dark:hover:bg-cyan-900 dark:hover:text-white';

                return sprintf(
                    '<a href="%s" title="%s" class="flex items-center gap-3 rounded-md px-4 py-3 text-sm font-medium %s"><svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">%s</svg><span x-show="! sidebarCollapsed" x-cloak>%s</span></a>',
                    e($href),
                    e($label),
                    e($classes),
                    $iconPath,
                    e($label),
                );
            };

            $icons = [
                'dashboard' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12 12 4.5 20.25 12M5.25 10.5V19.5h4.5v-5.25h4.5V19.5h4.5V10.5" />',
                'users' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0M18 8.25a2.625 2.625 0 0 1 0 5.25M21 20.25a5.25 5.25 0 0 0-3.75-5.03" />',
                'roles' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M12 3.75l7.5 3v5.25c0 4.5-3 7.5-7.5 8.25-4.5-.75-7.5-3.75-7.5-8.25V6.75l7.5-3Z" />',
                'projects' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M5.25 6.75v12h13.5v-12M8.25 10.5h7.5M8.25 14.25h4.5" />',
                'assign' => '<path stroke-linecap="round" stroke-linejoin="round" d="M7.5 7.5h9M7.5 12h9M7.5 16.5h4.5M4.5 4.5h15v15h-15z" />',
                'report' => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3.75h7.5L18.75 8.25v12h-12V3.75ZM14.25 3.75v4.5h4.5M9 13.5h6M9 16.5h6M9 10.5h2.25" />',
                'employees' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 12a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5ZM4.5 20.25a7.5 7.5 0 0 1 15 0M18.75 4.5v4.5M21 6.75h-4.5" />',
                'materials' => '<path stroke-linecap="round" stroke-linejoin="round" d="m12 3.75 7.5 4.125v8.25L12 20.25l-7.5-4.125v-8.25L12 3.75ZM4.5 7.875 12 12l7.5-4.125M12 20.25V12" />',
                'quantity' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12M6 12h12M4.5 4.5h15v15h-15z" />',
            ];
        @endphp

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <div
                x-data="{
                    sidebarCollapsed: localStorage.getItem('sidebar-collapsed') === 'true',
                    toggleSidebar() {
                        this.sidebarCollapsed = ! this.sidebarCollapsed;
                        localStorage.setItem('sidebar-collapsed', this.sidebarCollapsed ? 'true' : 'false');
                    },
                }"
                class="flex flex-col lg:flex-row"
            >
                <aside
                    class="w-full border-b border-cyan-200 bg-cyan-50 transition-all duration-200 dark:border-cyan-900 dark:bg-cyan-950 lg:border-b-0 lg:border-r"
                    :class="sidebarCollapsed ? 'lg:w-20' : 'lg:w-64'"
                >
                    <div class="px-4 py-6 sm:px-6 lg:px-4">
                        <div class="mb-6 flex items-center justify-between gap-3">
                            <div x-show="! sidebarCollapsed" x-cloak class="text-xs font-semibold uppercase tracking-wider text-cyan-800 dark:text-cyan-200">{{ __('Menu') }}</div>
                            <button
                                type="button"
                                @click="toggleSidebar()"
                                class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-md text-cyan-800 hover:bg-cyan-100 hover:text-cyan-950 focus:outline-none focus:ring-2 focus:ring-cyan-500 dark:text-cyan-200 dark:hover:bg-cyan-900 dark:hover:text-white lg:inline-flex"
                                :aria-label="sidebarCollapsed ? '{{ __('Expand sidebar') }}' : '{{ __('Collapse sidebar') }}'"
                            >
                                <svg class="h-5 w-5 transition-transform" :class="{ 'rotate-180': sidebarCollapsed }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.75h15M4.5 12h15M4.5 17.25h15M9.75 8.25 6 12l3.75 3.75" />
                                </svg>
                            </button>
                        </div>

                        <nav class="space-y-1">
                            {!! $sidebarLink(route('dashboard'), __('Dashboard'), request()->routeIs('dashboard'), $icons['dashboard']) !!}

                            @role('Admin')
                                {!! $sidebarLink(route('users.index'), __('Users'), request()->routeIs('users.*'), $icons['users']) !!}
                                {!! $sidebarLink(route('roles.index'), __('Roles'), request()->routeIs('roles.*'), $icons['roles']) !!}
                            @endrole

                            @role('HSE Site Officer')
                                {!! $sidebarLink(route('site-officer.projects.index'), __('My Projects'), request()->routeIs('site-officer.projects.*'), $icons['projects']) !!}
                                {!! $sidebarLink(route('site-officer.employee-assignments.create'), __('Assign to Employees'), request()->routeIs('site-officer.employee-assignments.create'), $icons['assign']) !!}
                                {!! $sidebarLink(route('site-officer.employee-assignments.index'), __('Employee Assignments'), request()->routeIs('site-officer.employee-assignments.index'), $icons['employees']) !!}
                                {!! $sidebarLink(route('site-officer.material-reports.index'), __('Material Balance Report'), request()->routeIs('site-officer.material-reports.*'), $icons['report']) !!}
                            @endrole

                            @hasanyrole('Admin|HSE Officer')
                                @role('Admin')
                                    {!! $sidebarLink(route('employees.index'), __('Employees'), request()->routeIs('employees.*'), $icons['employees']) !!}
                                    {!! $sidebarLink(route('projects.index'), __('Projects'), request()->routeIs('projects.*'), $icons['projects']) !!}
                                @endrole

                                {!! $sidebarLink(route('materials.index'), __('Materials'), request()->routeIs('materials.*'), $icons['materials']) !!}
                            @endhasanyrole

                            @role('HSE Officer')
                                {!! $sidebarLink(route('material-histories.index'), __('Material History'), request()->routeIs('material-histories.*'), $icons['report']) !!}
                                {!! $sidebarLink(route('material-assignments.create'), __('Assign Materials'), request()->routeIs('material-assignments.*'), $icons['assign']) !!}
                                {!! $sidebarLink(route('material-quantities.index'), __('Material Quantities'), request()->routeIs('material-quantities.*'), $icons['quantity']) !!}
                                {!! $sidebarLink(route('material-reports.inventory'), __('Material Inventory Report'), request()->routeIs('material-reports.inventory'), $icons['report']) !!}
                                {!! $sidebarLink(route('material-reports.index'), __('Project Reports'), request()->routeIs('material-reports.index', 'material-reports.show'), $icons['report']) !!}
                            @endrole
                        </nav>
                    </div>
                </aside>

                <div class="flex-1">
                    @isset($header)
                        <header class="bg-white shadow dark:bg-gray-800">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="py-8">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
