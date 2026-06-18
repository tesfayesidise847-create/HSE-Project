<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('My Project') }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Material balance report for assigned projects') }}</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto space-y-8 sm:px-6 lg:px-8">
            @forelse ($projects as $project)
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $project->project_name }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $project->project_code }}</p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('site-officer.projects.employees.index', $project) }}" class="inline-flex items-center justify-center gap-1.5 rounded-md border border-indigo-400 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-50 dark:border-indigo-500 dark:text-indigo-300 dark:hover:bg-indigo-900/20">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                                    {{ __('Manage Employees') }}
                                </a>
                                <a href="{{ route('site-officer.projects.show', $project) }}" class="inline-flex items-center justify-center rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                                    {{ __('View Details') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @php
                            $balances = $balancesByProject->get($project->id, collect());
                        @endphp

                        @if ($balances->isEmpty())
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No materials assigned to this project yet.') }}</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Material Name') }}</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Unit of Measurement') }}</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Assigned Balance') }}</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Employees Distributed') }}</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Physical Balance') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($balances as $balance)
                                            <tr>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $balance['material']->material_name }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $balance['material']->unitOfMeasure?->name ?? __('-') }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-500 dark:text-gray-300">{{ number_format($balance['received']) }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm text-gray-500 dark:text-gray-300">{{ number_format($balance['distributed']) }}</td>
                                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold text-emerald-600 dark:text-emerald-400">{{ number_format($balance['available']) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                        {{ __('No projects assigned to you.') }}
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
