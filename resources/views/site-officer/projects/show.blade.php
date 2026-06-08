<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $project->project_name }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Site') }}: {{ $project->project_code }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('site-officer.employee-assignments.create') }}?project_id={{ $project->id }}" class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">{{ __('Assign to Employees') }}</a>
                <a href="{{ route('site-officer.projects.index') }}" class="inline-flex items-center rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-900">{{ __('Back') }}</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 space-y-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
            @endif

            <div class="overflow-hidden shadow-sm sm:rounded-lg bg-white dark:bg-gray-800">
                <div class="p-6">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('Current site balance') }}</h3>
                    @if ($balances->isEmpty())
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No materials on this site yet.') }}</p>
                    @else
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">{{ __('Material') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">{{ __('Received') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">{{ __('Distributed') }}</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-300">{{ __('Available') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($balances as $balance)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $balance['material']->material_name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-300">{{ $balance['received'] }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-300">{{ $balance['distributed'] }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-emerald-600 dark:text-emerald-400">{{ $balance['available'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid gap-8 lg:grid-cols-2">
            <div class="overflow-hidden shadow-sm sm:rounded-lg bg-white dark:bg-gray-800 p-6">
                <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Incoming assignments') }}</h3>
                @forelse ($incomingHistory as $assignment)
                    <div class="mb-3 text-sm">
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $assignment->material->material_name }}</span>
                        <span class="text-gray-500 dark:text-gray-400">× {{ $assignment->quantity }} · {{ $assignment->created_at->format('M d, Y') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('None yet.') }}</p>
                @endforelse
            </div>

            <div class="overflow-hidden shadow-sm sm:rounded-lg bg-white dark:bg-gray-800 p-6">
                <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Employee distributions') }}</h3>
                @forelse ($employeeAssignments as $assignment)
                    <div class="mb-3 text-sm">
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $assignment->material->material_name }}</span>
                        <span class="text-gray-500 dark:text-gray-400">× {{ $assignment->quantity }} → {{ $assignment->employee->first_name }} {{ $assignment->employee->last_name }} · {{ $assignment->assigned_date->format('M d, Y') }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('None yet.') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
