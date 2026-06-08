<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="space-y-8">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <x-dashboard.stat-card :label="__('Total Users')" :value="number_format($stats['total_users'])" :hint="__('Registered system accounts')" color="indigo" />
            <x-dashboard.stat-card :label="__('Total Roles')" :value="number_format($stats['total_roles'])" :hint="__('Access groups configured')" color="slate" />
            <x-dashboard.stat-card :label="__('Projects')" :value="number_format($stats['total_projects'])" :hint="__('Projects under management')" color="blue" />
            <x-dashboard.stat-card :label="__('Employees')" :value="number_format($stats['total_employees'])" :hint="__('Employees available for assignments')" color="emerald" />
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800 xl:col-span-2">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Users vs Roles') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Number of users assigned to each role') }}</p>
                </div>
                <div class="space-y-4 p-6">
                    @forelse ($stats['roles'] as $role)
                        <div>
                            <div class="mb-2 flex items-center justify-between gap-4">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</span>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($role->users_count) }}</span>
                            </div>
                            <div class="h-3 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                                <div class="h-full rounded-full bg-indigo-600 dark:bg-indigo-400" style="width: {{ ($role->users_count / $stats['max_role_users']) * 100 }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No roles have been created yet.') }}</p>
                    @endforelse
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Material Overview') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Current material and assignment totals') }}</p>
                </div>
                <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="flex items-center justify-between px-6 py-4">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('Material Types') }}</dt>
                        <dd class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stats['total_materials']) }}</dd>
                    </div>
                    <div class="flex items-center justify-between px-6 py-4">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('Head Office Stock') }}</dt>
                        <dd class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stats['head_office_stock']) }}</dd>
                    </div>
                    <div class="flex items-center justify-between px-6 py-4">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('Assigned to Projects') }}</dt>
                        <dd class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stats['assigned_to_projects']) }}</dd>
                    </div>
                    <div class="flex items-center justify-between px-6 py-4">
                        <dt class="text-sm text-gray-500 dark:text-gray-400">{{ __('Assigned to Employees') }}</dt>
                        <dd class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stats['assigned_to_employees']) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Recent Users') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($stats['latest_users'] as $user)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $user->roles->first()?->name ?? __('No role') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-6 text-sm text-gray-500 dark:text-gray-400">{{ __('No users available yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Recent Projects') }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($stats['latest_projects'] as $project)
                                <tr>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $project->project_name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $project->project_code }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $project->siteOfficer?->name ?? __('Unassigned') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-6 text-sm text-gray-500 dark:text-gray-400">{{ __('No projects available yet.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
