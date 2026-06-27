<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Admin Dashboard') }}</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ __('System overview and quick statistics') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-500"></span>
                    {{ __('System Active') }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 animate-fade-in">

        {{-- ── Stat Cards ─────────────────────────────────────────── --}}
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

            {{-- Users --}}
            <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-indigo-500 to-violet-500"></div>
                <div class="flex items-start justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-900/30">
                        <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/>
                        </svg>
                    </div>
                    <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">Users</span>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ number_format($stats['total_users']) }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Registered system accounts') }}</p>
                </div>
            </div>

            {{-- Roles --}}
            <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-slate-400 to-slate-600"></div>
                <div class="flex items-start justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800">
                        <svg class="h-5 w-5 text-slate-600 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M12 3.75l7.5 3v5.25c0 4.5-3 7.5-7.5 8.25-4.5-.75-7.5-3.75-7.5-8.25V6.75l7.5-3Z"/>
                        </svg>
                    </div>
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">Roles</span>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ number_format($stats['total_roles']) }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Access groups configured') }}</p>
                </div>
            </div>

            {{-- Projects --}}
            <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-blue-500 to-cyan-500"></div>
                <div class="flex items-start justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/30">
                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M5.25 6.75v12h13.5v-12M8.25 10.5h7.5M8.25 14.25h4.5"/>
                        </svg>
                    </div>
                    <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">Projects</span>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ number_format($stats['total_projects']) }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Projects under management') }}</p>
                </div>
            </div>

            {{-- Employees --}}
            <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                <div class="flex items-start justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-900/30">
                        <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5ZM4.5 20.25a7.5 7.5 0 0 1 15 0M18.75 4.5v4.5M21 6.75h-4.5"/>
                        </svg>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">Staff</span>
                </div>
                <div class="mt-4">
                    <p class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ number_format($stats['total_employees']) }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Available for assignments') }}</p>
                </div>
            </div>

        </div>

        {{-- ── Material Stock Breakdown + Role Distribution ───────── --}}
        <div class="grid gap-6 xl:grid-cols-3">

            {{-- Material Distribution — visual donut-style breakdown --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Material Distribution') }}</h3>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ __('Stock breakdown across locations') }}</p>
                </div>

                @php
                    $totalStock = max(1, $stats['head_office_stock'] + $stats['assigned_to_projects'] + $stats['assigned_to_employees']);
                    $segments = [
                        ['label' => __('Head Office'),        'value' => $stats['head_office_stock'],     'color' => 'bg-blue-500',    'text' => 'text-blue-600 dark:text-blue-400',    'bg' => 'bg-blue-50 dark:bg-blue-900/20'],
                        ['label' => __('Assigned to Projects'),'value' => $stats['assigned_to_projects'],  'color' => 'bg-amber-500',   'text' => 'text-amber-600 dark:text-amber-400',  'bg' => 'bg-amber-50 dark:bg-amber-900/20'],
                        ['label' => __('Assigned to Staff'),  'value' => $stats['assigned_to_employees'], 'color' => 'bg-emerald-500', 'text' => 'text-emerald-600 dark:text-emerald-400','bg' => 'bg-emerald-50 dark:bg-emerald-900/20'],
                    ];
                @endphp

                {{-- Stacked bar --}}
                <div class="px-6 pt-5">
                    <div class="flex h-3 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                        @foreach ($segments as $seg)
                            @php $pct = round(($seg['value'] / $totalStock) * 100, 1); @endphp
                            @if ($pct > 0)
                                <div class="{{ $seg['color'] }} transition-all duration-700" style="width: {{ $pct }}%"></div>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Legend --}}
                <div class="space-y-3 px-6 py-5">
                    @foreach ($segments as $seg)
                        @php $pct = round(($seg['value'] / $totalStock) * 100, 1); @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $seg['color'] }}"></span>
                                <span class="truncate text-sm text-gray-600 dark:text-gray-400">{{ $seg['label'] }}</span>
                            </div>
                            <div class="flex items-center gap-2 ml-2 shrink-0">
                                <span class="text-sm font-bold {{ $seg['text'] }}">{{ number_format($seg['value']) }}</span>
                                <span class="text-xs text-gray-400 dark:text-gray-600">{{ $pct }}%</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="border-t border-gray-100 pt-3 dark:border-gray-800">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Total Material Types') }}</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_materials']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Users per Role — bar chart --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 xl:col-span-2">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Users by Role') }}</h3>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ __('Number of users assigned to each access group') }}</p>
                    </div>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-cyan-50 dark:bg-cyan-900/20">
                        <svg class="h-4 w-4 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                        </svg>
                    </div>
                </div>
                <div class="space-y-5 p-6">
                    @forelse ($stats['roles'] as $role)
                        @php
                            $pct = $stats['max_role_users'] > 0 ? ($role->users_count / $stats['max_role_users']) * 100 : 0;
                            $totalPct = $stats['total_users'] > 0 ? round(($role->users_count / $stats['total_users']) * 100) : 0;
                        @endphp
                        <div>
                            <div class="mb-2 flex items-center justify-between gap-4">
                                <div class="flex min-w-0 items-center gap-2">
                                    <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-cyan-50 text-[10px] font-bold text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400">
                                        {{ strtoupper(substr($role->name, 0, 1)) }}
                                    </span>
                                    <span class="truncate text-sm font-medium text-gray-800 dark:text-gray-200">{{ $role->name }}</span>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($role->users_count) }}</span>
                                    <span class="w-8 text-right text-xs text-gray-400 dark:text-gray-600">{{ $totalPct }}%</span>
                                </div>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                                <div
                                    class="h-full rounded-full bg-gradient-to-r from-cyan-500 to-teal-400 transition-all duration-700 ease-out"
                                    style="width: {{ $pct }}%"
                                ></div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M12 3.75l7.5 3v5.25c0 4.5-3 7.5-7.5 8.25-4.5-.75-7.5-3.75-7.5-8.25V6.75l7.5-3Z"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No roles have been created yet.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ── Project Status Overview ─────────────────────────────── --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Project Coverage') }}</h3>
                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ __('Ratio of assigned vs unassigned projects') }}</p>
                </div>
                <a href="{{ route('projects.index') }}" class="text-xs font-medium text-cyan-600 hover:text-cyan-500 dark:text-cyan-400">
                    {{ __('View all →') }}
                </a>
            </div>

            @php
                $assignedCount   = $stats['latest_projects']->whereNotNull('site_officer_id')->count();
                $unassignedCount = $stats['latest_projects']->whereNull('site_officer_id')->count();
                $totalShown      = $stats['latest_projects']->count();
                $assignedPct     = $totalShown > 0 ? round(($assignedCount / $totalShown) * 100) : 0;
            @endphp

            <div class="grid divide-y divide-gray-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0 dark:divide-gray-800">
                <div class="flex flex-col items-center justify-center gap-1 px-6 py-6">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_projects'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Total Projects') }}</p>
                </div>
                <div class="flex flex-col items-center justify-center gap-1 px-6 py-6">
                    <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400">{{ $assignedCount }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('With Site Officer') }}</p>
                </div>
                <div class="flex flex-col items-center justify-center gap-1 px-6 py-6">
                    <p class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $unassignedCount }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Unassigned') }}</p>
                </div>
            </div>

            {{-- Coverage bar --}}
            <div class="border-t border-gray-100 px-6 py-4 dark:border-gray-800">
                <div class="mb-1.5 flex items-center justify-between">
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('Assignment coverage') }}</span>
                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">{{ $assignedPct }}%</span>
                </div>
                <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-700" style="width: {{ $assignedPct }}%"></div>
                </div>
            </div>
        </div>

        {{-- ── Recent Users + Recent Projects ─────────────────────── --}}
        <div class="grid gap-6 xl:grid-cols-2">

            {{-- Recent Users --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-900/30">
                            <svg class="h-3.5 w-3.5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Recent Users') }}</h3>
                    </div>
                    <a href="{{ route('users.index') }}" class="text-xs font-medium text-cyan-600 hover:text-cyan-500 dark:text-cyan-400">{{ __('View all →') }}</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($stats['latest_users'] as $user)
                        <div class="flex items-center gap-3 px-6 py-3.5 transition-colors hover:bg-gray-50/70 dark:hover:bg-gray-800/50">
                            <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-cyan-500 to-teal-600 text-xs font-bold text-white shadow-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                            @if ($user->roles->first())
                                <span class="shrink-0 rounded-full bg-cyan-50 px-2.5 py-0.5 text-xs font-medium text-cyan-800 dark:bg-cyan-900/30 dark:text-cyan-300">
                                    {{ $user->roles->first()->name }}
                                </span>
                            @else
                                <span class="shrink-0 rounded-full bg-gray-100 px-2.5 py-0.5 text-xs text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                    {{ __('No role') }}
                                </span>
                            @endif
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center px-6 py-10 text-center">
                            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No users available yet.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Projects --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                    <div class="flex items-center gap-2">
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/30">
                            <svg class="h-3.5 w-3.5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M5.25 6.75v12h13.5v-12M8.25 10.5h7.5M8.25 14.25h4.5"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('Recent Projects') }}</h3>
                    </div>
                    <a href="{{ route('projects.index') }}" class="text-xs font-medium text-cyan-600 hover:text-cyan-500 dark:text-cyan-400">{{ __('View all →') }}</a>
                </div>
                <div class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($stats['latest_projects'] as $project)
                        <div class="flex items-center gap-3 px-6 py-3.5 transition-colors hover:bg-gray-50/70 dark:hover:bg-gray-800/50">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 dark:bg-blue-900/30">
                                <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M5.25 6.75v12h13.5v-12"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-gray-900 dark:text-gray-100">{{ $project->project_name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $project->project_code }}</p>
                            </div>
                            @if ($project->siteOfficer)
                                <span class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 max-w-[110px] truncate">
                                    {{ $project->siteOfficer->name }}
                                </span>
                            @else
                                <span class="shrink-0 rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                    {{ __('Unassigned') }}
                                </span>
                            @endif
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center px-6 py-10 text-center">
                            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M5.25 6.75v12h13.5v-12"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No projects available yet.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-app-layout>