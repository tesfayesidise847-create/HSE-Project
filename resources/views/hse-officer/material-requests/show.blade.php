<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Material Request Details') }}</h2>
<<<<<<< HEAD
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Review request details and approve or reject') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('hse-officer.material-requests.index') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600">{{ __('Back to Requests') }}</a>
=======
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Review the full details of this material request') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('hse-officer.material-requests.index') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">{{ __('Back to Requests') }}</a>
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto space-y-6">
            {{-- Status Banner --}}
<<<<<<< HEAD
            <div class="rounded-xl p-6 shadow-sm
                @if ($request->isPending())
                    bg-yellow-50 border border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800
                @elseif ($request->isApproved())
                    bg-green-50 border border-green-200 dark:bg-green-900/20 dark:border-green-800
                @elseif ($request->isRejected())
                    bg-red-50 border border-red-200 dark:bg-red-900/20 dark:border-red-800
=======
            <div class="rounded-xl p-6 shadow-sm border
                @if ($request->isPending())
                    bg-yellow-50 border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-800
                @elseif ($request->isApproved())
                    bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-800
                @elseif ($request->isRejected())
                    bg-red-50 border-red-200 dark:bg-red-900/20 dark:border-red-800
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
                @endif
            ">
                <div class="flex items-center gap-3">
                    @if ($request->isPending())
                        <svg class="h-8 w-8 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    @elseif ($request->isApproved())
                        <svg class="h-8 w-8 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    @elseif ($request->isRejected())
                        <svg class="h-8 w-8 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    @endif
                    <div>
                        <h3 class="text-lg font-semibold
                            @if ($request->isPending()) text-yellow-800 dark:text-yellow-300
                            @elseif ($request->isApproved()) text-green-800 dark:text-green-300
                            @elseif ($request->isRejected()) text-red-800 dark:text-red-300
                            @endif
                        ">
                            @if ($request->isPending())
<<<<<<< HEAD
                                {{ __('Request Pending Approval') }}
                            @elseif ($request->isApproved())
                                {{ __('Request Approved') }}
                            @elseif ($request->isRejected())
                                {{ __('Request Rejected') }}
=======
                                {{ __('Pending Review') }}
                            @elseif ($request->isApproved())
                                {{ __('Approved') }}
                            @elseif ($request->isRejected())
                                {{ __('Rejected') }}
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
                            @endif
                        </h3>
                        <p class="text-sm
                            @if ($request->isPending()) text-yellow-700 dark:text-yellow-400
                            @elseif ($request->isApproved()) text-green-700 dark:text-green-400
                            @elseif ($request->isRejected()) text-red-700 dark:text-red-400
                            @endif
                        ">
                            @if ($request->isPending())
<<<<<<< HEAD
                                {{ __('Awaiting review. You can approve or reject below.') }}
                            @elseif ($request->isApproved())
                                {{ __('Approved by') }} {{ $request->approver?->name ?? '—' }} {{ __('on') }} {{ $request->approved_at?->format('M d, Y \a\t h:i A') ?? '—' }}
                            @elseif ($request->isRejected())
                                {{ __('Rejected by') }} {{ $request->approver?->name ?? '—' }} {{ __('on') }} {{ $request->approved_at?->format('M d, Y \a\t h:i A') ?? '—' }}
=======
                                {{ __('This request is awaiting your review.') }}
                            @elseif ($request->isApproved())
                                {{ __('Approved by you on') }} {{ $request->approved_at?->format('M d, Y \a\t h:i A') ?? '—' }}
                            @elseif ($request->isRejected())
                                {{ __('Rejected by you on') }} {{ $request->approved_at?->format('M d, Y \a\t h:i A') ?? '—' }}
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
                            @endif
                        </p>
                    </div>
                </div>
            </div>

<<<<<<< HEAD
            {{-- Error Alerts --}}
            @if ($errors->any())
                <div class="rounded-xl bg-red-50 p-4 border border-red-200 dark:bg-red-900/20 dark:border-red-800">
                    <ul class="list-disc pl-5 text-sm text-red-700 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Request Details --}}
=======
            {{-- Request Information --}}
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
            <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Request Information') }}</h3>
                </div>
                <div class="p-6">
                    <dl class="divide-y divide-gray-100 dark:divide-gray-700">
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Request Date') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $request->created_at->format('F d, Y \a\t h:i A') }}</dd>
                        </div>
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
<<<<<<< HEAD
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Project / Site') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                <span class="font-medium">{{ $request->project->project_code }}</span> — {{ $request->project->project_name }}
=======
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Requested By') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                <span class="font-medium">{{ $request->requester->name }}</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->requester->email }}</p>
                            </dd>
                        </div>
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Project') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                <span class="font-medium">{{ $request->project->project_code }}</span> — {{ $request->project->project_name }}
                                @if ($request->project->siteOfficer)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Site Officer: {{ $request->project->siteOfficer->name }}</p>
                                @endif
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
                            </dd>
                        </div>
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Material') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                <span class="font-medium">{{ $request->material->material_name }}</span>
                                @if ($request->material->material_description)
                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $request->material->material_description }}</p>
                                @endif
                            </dd>
                        </div>
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Quantity Requested') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-0.5 text-sm font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
<<<<<<< HEAD
                                    {{ $request->quantity }} {{ $request->material->unitOfMeasure?->name ?? __('units') }}
                                </span>
                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">({{ __('Head Office Stock Available: :qty', ['qty' => $request->material->quantity]) }})</span>
                            </dd>
                        </div>
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Requested By') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $request->requester->name }}</dd>
=======
                                    {{ $request->quantity }}
                                </span>
                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">({{ __('Available in stock') }}: {{ $request->material->quantity }})</span>
                            </dd>
                        </div>
                        <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Status') }}</dt>
                            <dd class="mt-1 text-sm sm:col-span-2 sm:mt-0">
                                @if ($request->isPending())
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">{{ __('Pending') }}</span>
                                @elseif ($request->isApproved())
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300">{{ __('Approved') }}</span>
                                @elseif ($request->isRejected())
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-300">{{ __('Rejected') }}</span>
                                @endif
                            </dd>
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
                        </div>
                    </dl>
                </div>
            </div>

<<<<<<< HEAD
            {{-- Justification --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Justification / Description') }}</h3>
                </div>
                <div class="p-6 text-sm text-gray-700 dark:text-gray-300">
                    {{ $request->description ?: __('No description provided.') }}
=======
            {{-- Description / Justification --}}
            <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Description / Justification') }}</h3>
                </div>
                <div class="p-6">
                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300">
                        {{ $request->description ?: __('No description provided.') }}
                    </div>
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
                </div>
            </div>

            {{-- Employee File --}}
            @if ($request->employee_file)
                <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800">
                    <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Employee List') }}</h3>
<<<<<<< HEAD
=======
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Site Officer uploaded an employee list for distribution') }}</p>
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
                    </div>
                    <div class="p-6">
                        <a href="{{ asset('storage/' . $request->employee_file) }}" target="_blank" class="inline-flex items-center gap-2 rounded-md bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300 dark:hover:bg-indigo-900/50">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            {{ __('Download Employee Excel File') }}
                        </a>
                    </div>
                </div>
            @endif

            {{-- Rejection Reason --}}
            @if ($request->isRejected() && $request->rejection_reason)
                <div class="overflow-hidden rounded-xl bg-red-50 border border-red-200 shadow-sm dark:bg-red-900/20 dark:border-red-800">
                    <div class="border-b border-red-200 px-6 py-4 dark:border-red-800">
                        <h3 class="text-base font-semibold text-red-800 dark:text-red-300">{{ __('Rejection Reason') }}</h3>
                    </div>
<<<<<<< HEAD
                    <div class="p-6 text-sm text-red-700 dark:text-red-400">
                        {{ $request->rejection_reason }}
=======
                    <div class="p-6">
                        <p class="text-sm text-red-700 dark:text-red-400">{{ $request->rejection_reason }}</p>
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
                    </div>
                </div>
            @endif

<<<<<<< HEAD
            {{-- Pending Approval Actions --}}
            @if ($request->isPending())
                <div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-800 p-6">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Decision Panel') }}</h3>
                    <div class="flex flex-wrap gap-4 items-center justify-between">
                        {{-- Approve form --}}
                        <form method="POST" action="{{ route('hse-officer.material-requests.approve', $request) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                onclick="return confirm('{{ __('Approve this material request? This will deduct quantity from head office stock.') }}')">
                                {{ __('Approve Request') }}
                            </button>
                        </form>

                        {{-- Reject form --}}
                        <div x-data="{ open: false }" class="flex-1 max-w-md text-right">
                            <button type="button" @click="open = !open" class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                                {{ __('Reject Request...') }}
                            </button>

                            <div x-show="open" x-cloak class="mt-4 text-left p-4 border border-gray-250 rounded-lg dark:border-gray-700">
                                <form method="POST" action="{{ route('hse-officer.material-requests.reject', $request) }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <x-input-label for="rejection_reason_page" :value="__('Rejection Reason')" />
                                        <textarea id="rejection_reason_page" name="rejection_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-red-400" required placeholder="{{ __('Provide a reason for rejection...') }}"></textarea>
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="open = false" class="text-xs text-gray-500 dark:text-gray-400 hover:underline">{{ __('Cancel') }}</button>
                                        <button type="submit" class="inline-flex items-center rounded bg-red-600 px-3 py-1 text-xs font-semibold text-white shadow-sm hover:bg-red-500">
                                            {{ __('Submit Rejection') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
=======
            {{-- Approval Info --}}
            @if ($request->isApproved())
                <div class="overflow-hidden rounded-xl bg-green-50 border border-green-200 shadow-sm dark:bg-green-900/20 dark:border-green-800">
                    <div class="border-b border-green-200 px-6 py-4 dark:border-green-800">
                        <h3 class="text-base font-semibold text-green-800 dark:text-green-300">{{ __('Approval Information') }}</h3>
                    </div>
                    <div class="p-6">
                        <dl class="divide-y divide-green-100 dark:divide-green-800/50">
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium text-green-600 dark:text-green-400">{{ __('Approved By') }}</dt>
                                <dd class="mt-1 text-sm text-green-900 dark:text-green-100 sm:col-span-2 sm:mt-0">{{ $request->approver?->name ?? '—' }}</dd>
                            </div>
                            <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm font-medium text-green-600 dark:text-green-400">{{ __('Approved At') }}</dt>
                                <dd class="mt-1 text-sm text-green-900 dark:text-green-100 sm:col-span-2 sm:mt-0">{{ $request->approved_at?->format('F d, Y \a\t h:i A') ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @endif

            {{-- Actions for Pending Requests --}}
            @if ($request->isPending())
                <div class="flex items-center justify-end gap-3 rounded-xl bg-white p-6 shadow-sm dark:bg-gray-800">
                    <button type="button" @click="$dispatch('open-reject-modal', { id: {{ $request->id }}, material: '{{ $request->material->material_name }}', requester: '{{ $request->requester->name }}' })"
                        class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                        <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                        {{ __('Reject Request') }}
                    </button>

                    <form method="POST" action="{{ route('hse-officer.material-requests.approve', $request) }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500"
                            onclick="return confirm('{{ __('Approve this material request? This will deduct quantity from head office stock.') }}')">
                            <svg class="mr-1.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            {{ __('Approve & Send to Site') }}
                        </button>
                    </form>
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
                </div>
            @endif
        </div>
    </div>
<<<<<<< HEAD
</x-app-layout>
=======

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
                    <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                        {{ __('Reject Request') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
>>>>>>> 92683a169498c61dae9e5be240231f1e2eb13465
