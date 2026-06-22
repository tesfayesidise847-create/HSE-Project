<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('My Material Requests') }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Track your material requests and their approval status') }}</p>
            </div>
            <a href="{{ route('site-officer.material-requests.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">{{ __('New Request') }}</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800">
            @if ($requests->isEmpty())
                <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                    {{ __('No material requests yet.') }}
                    <a href="{{ route('site-officer.material-requests.create') }}" class="ml-1 text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ __('Create your first request') }}</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Date') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Project') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Material') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Quantity') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Approved By') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Employee File') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($requests as $request)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $request->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $request->project->project_code }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        <a href="{{ route('site-officer.material-requests.show', $request) }}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">
                                            {{ $request->material->material_name }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $request->quantity }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        @if ($request->isPending())
                                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                {{ __('Pending') }}
                                            </span>
                                        @elseif ($request->isApproved())
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                {{ __('Approved') }}
                                            </span>
                                        @elseif ($request->isRejected())
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-300" title="{{ $request->rejection_reason }}">
                                                {{ __('Rejected') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $request->approver?->name ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if ($request->employee_file)
                                            <a href="{{ asset('storage/' . $request->employee_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ __('Download') }}</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                                @if ($request->isRejected() && $request->rejection_reason)
                                    <tr class="bg-red-50 dark:bg-red-900/10">
                                        <td colspan="7" class="px-6 py-2 text-sm text-red-600 dark:text-red-400">
                                            <strong>{{ __('Rejection reason:') }}</strong> {{ $request->rejection_reason }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>