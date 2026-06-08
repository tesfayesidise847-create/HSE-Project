<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Employee Assignment History') }}</h2>
            <a href="{{ route('site-officer.employee-assignments.create') }}" class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">{{ __('New assignment') }}</a>
        </div>
    </x-slot>

    <div
        class="py-12 space-y-8"
        x-data="employeeHistoryMixin()"
        @keydown.escape.window="closeHistoryModal()"
    >
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg bg-white dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('View employee assignment history') }}</h3>
                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($employees as $employee)
                            <button
                                type="button"
                                @click="openHistoryModal({{ $employee->id }})"
                                class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 text-left text-sm hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-900"
                            >
                                <span>
                                    <span class="block font-medium text-gray-900 dark:text-gray-100">{{ $employee->first_name }} {{ $employee->last_name }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $employee->job_title }}</span>
                                </span>
                                <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400">{{ __('History') }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg bg-white dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ __('All assignments') }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Date') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Project') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Material') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Quantity') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Employee') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($assignments as $assignment)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $assignment->assigned_date->format('M d, Y') }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $assignment->project->project_code }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $assignment->material->material_name }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $assignment->quantity }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $assignment->employee->first_name }} {{ $assignment->employee->last_name }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                            <button
                                                type="button"
                                                @click="openHistoryModal({{ $assignment->employee_id }})"
                                                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-white hover:bg-indigo-500"
                                            >
                                                {{ __('History') }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">{{ __('No assignments found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $assignments->links() }}</div>
                </div>
            </div>
        </div>

        @include('site-officer.partials.employee-history-popup')
    </div>

    @include('site-officer.partials.employee-history-script')
</x-app-layout>
