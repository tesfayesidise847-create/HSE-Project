<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Material Requests') }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Review and approve/reject material requests from Site Officers') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800">
            @if ($requests->isEmpty())
                <div class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                    {{ __('No material requests yet.') }}
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Date') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Requested By') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Project') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Material') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Quantity') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Justification') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Employee File') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-300">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($requests as $request)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $request->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $request->requester->name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $request->project->project_code }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $request->material->material_name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $request->quantity }}
                                    </td>
                                    <td class="max-w-xs px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        <span title="{{ $request->description }}" class="line-clamp-2">{{ $request->description }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if ($request->employee_file)
                                            <a href="{{ asset('storage/' . $request->employee_file) }}" target="_blank" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ __('Download') }}</a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        @if ($request->isPending())
                                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                {{ __('Pending') }}
                                            </span>
                                        @elseif ($request->isApproved())
                                            <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                {{ __('Approved') }}
                                                @if ($request->approved_at)
                                                    <span class="ml-1">{{ $request->approved_at->format('M d') }}</span>
                                                @endif
                                            </span>
                                        @elseif ($request->isRejected())
                                            <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                {{ __('Rejected') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        @if ($request->isPending())
                                            <div class="flex items-center gap-2">
                                                {{-- Approve Form --}}
                                                <form method="POST" action="{{ route('hse-officer.material-requests.approve', $request) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center rounded-md bg-green-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                                        onclick="return confirm('{{ __('Approve this material request? This will deduct quantity from head office stock.') }}')">
                                                        {{ __('Approve') }}
                                                    </button>
                                                </form>

                                                {{-- Reject Button (opens modal) --}}
                                                <button type="button" @click="$dispatch('open-reject-modal', { id: {{ $request->id }}, material: '{{ $request->material->material_name }}', requester: '{{ $request->requester->name }}' })"
                                                    class="inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                                    {{ __('Reject') }}
                                                </button>
                                            </div>
                                        @elseif ($request->isRejected() && $request->rejection_reason)
                                            <span class="text-xs text-red-600 dark:text-red-400 cursor-help" title="{{ $request->rejection_reason }}">
                                                {{ Str::limit($request->rejection_reason, 40) }}
                                            </span>
                                        @elseif ($request->isApproved())
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ __('by') }} {{ $request->approver?->name ?? '—' }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Reject Modal --}}
    <div x-data="{ open: false, requestId: null, material: '', requester: '' }"
         x-on:open-reject-modal.window="open = true; requestId = $event.detail.id; material = $event.detail.material; requester = $event.detail.requester"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-900/50"
         @keydown.escape.window="open = false"
    >
        <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl dark:bg-gray-800" @click.outside="open = false">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Reject Material Request') }}</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Rejecting request from') }} <strong x-text="requester"></strong> {{ __('for') }} <strong x-text="material"></strong>.
            </p>

            <form method="POST" :action="'{{ route('hse-officer.material-requests.reject', ['materialRequest' => '__ID__']) }}'.replace('__ID__', requestId)" class="mt-4 space-y-4">
                @csrf
                <div>
                    <x-input-label for="rejection_reason" :value="__('Rejection Reason')" />
                    <textarea id="rejection_reason" name="rejection_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-red-400" required placeholder="{{ __('Provide a reason for rejection...') }}"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <button type="button" @click="open = false" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        {{ __('Reject Request') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>