<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Material Requests') }}</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ __('Review and approve or reject material requests from Site Officers') }}</p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Summary badges --}}
                @php
                    $pendingCount  = $requests->where(fn($r) => $r->isPending())->count();
                    $approvedCount = $requests->where(fn($r) => $r->isApproved())->count();
                    $rejectedCount = $requests->where(fn($r) => $r->isRejected())->count();
                @endphp
                @if ($pendingCount > 0)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500 pulse-dot"></span>
                        {{ $pendingCount }} {{ __('Pending') }}
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="animate-fade-in space-y-4">

        @if ($requests->isEmpty())
            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-300 bg-white py-16 dark:border-gray-700 dark:bg-gray-900">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m12 3.75 7.5 4.125v8.25L12 20.25l-7.5-4.125v-8.25L12 3.75Z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ __('No material requests') }}</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Material requests from Site Officers will appear here.') }}</p>
            </div>

        @else
            {{-- Premium table --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-card dark:border-gray-800 dark:bg-gray-900">
                <div class="overflow-x-auto">
                    <table class="min-w-full premium-table">
                        <thead>
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Requested By') }}</th>
                                <th>{{ __('Project') }}</th>
                                <th>{{ __('Material') }}</th>
                                <th>{{ __('Qty') }}</th>
                                <th>{{ __('Justification') }}</th>
                                <th>{{ __('File') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-900">
                            @foreach ($requests as $request)
                                <tr class="group">
                                    <td class="whitespace-nowrap px-5 py-4 text-xs text-gray-500 dark:text-gray-400">
                                        <div class="font-medium text-gray-800 dark:text-gray-200">{{ $request->created_at->format('M d, Y') }}</div>
                                        <div class="text-[11px] text-gray-400">{{ $request->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-eec-500 to-teal-600 text-[10px] font-bold text-white shadow-sm">
                                                {{ strtoupper(substr($request->requester->name, 0, 1)) }}
                                            </span>
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $request->requester->name }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                            {{ $request->project->project_code }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $request->material->material_name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center rounded-full bg-eec-50 px-2.5 py-0.5 text-xs font-semibold text-eec-700 dark:bg-eec-900/20 dark:text-eec-300">
                                            {{ $request->quantity }}
                                        </span>
                                    </td>
                                    <td class="max-w-[180px] px-5 py-4">
                                        <span title="{{ $request->description }}" class="line-clamp-2 text-xs text-gray-600 dark:text-gray-400">
                                            {{ $request->description ?: '—' }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if ($request->employee_file)
                                            <a href="{{ asset('storage/' . $request->employee_file) }}" target="_blank"
                                               class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 hover:bg-indigo-100 transition-colors dark:bg-indigo-900/30 dark:text-indigo-300">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                                {{ __('File') }}
                                            </a>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if ($request->isPending())
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 pulse-dot"></span>
                                                {{ __('Pending') }}
                                            </span>
                                        @elseif ($request->isApproved())
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                                <svg class="h-3 w-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/></svg>
                                                {{ __('Approved') }}
                                            </span>
                                        @elseif ($request->isRejected())
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-800 dark:bg-rose-900/30 dark:text-rose-300">
                                                <svg class="h-3 w-3 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/></svg>
                                                {{ __('Rejected') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if ($request->isPending())
                                            <div class="flex items-center gap-1.5">
                                                {{-- View Details --}}
                                                <button type="button"
                                                    @click="$dispatch('open-details-modal', {
                                                        request: {
                                                            id: {{ $request->id }},
                                                            date: '{{ $request->created_at->format('F d, Y \a\t h:i A') }}',
                                                            requester: {{ json_encode($request->requester->name) }},
                                                            project_code: {{ json_encode($request->project->project_code) }},
                                                            project_name: {{ json_encode($request->project->project_name) }},
                                                            material_name: {{ json_encode($request->material->material_name) }},
                                                            material_description: {{ json_encode($request->material->material_description ?? '') }},
                                                            quantity: {{ $request->quantity }},
                                                            unit: {{ json_encode($request->material->unitOfMeasure?->name ?? __('units')) }},
                                                            available_qty: {{ $request->material->quantity }},
                                                            description: {{ json_encode($request->description ?? '') }},
                                                            employee_file: {{ json_encode($request->employee_file ? asset('storage/' . $request->employee_file) : '') }},
                                                            status: {{ json_encode($request->status) }},
                                                            rejection_reason: {{ json_encode($request->rejection_reason ?? '') }},
                                                            approver_name: {{ json_encode($request->approver?->name ?? '') }},
                                                            approved_at: {{ json_encode($request->approved_at ? $request->approved_at->format('M d, Y \a\t h:i A') : '') }}
                                                        }
                                                    })"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-eec-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-eec-500 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-eec-500 focus:ring-offset-1 dark:focus:ring-offset-gray-900">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                                    {{ __('View') }}
                                                </button>

                                                {{-- Approve --}}
                                                <form method="POST" action="{{ route('hse-officer.material-requests.approve', $request) }}" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        onclick="return confirm('{{ __('Approve this material request? This will deduct quantity from head office stock.') }}')"
                                                        class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-emerald-500 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 dark:focus:ring-offset-gray-900">
                                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                                                        {{ __('Approve') }}
                                                    </button>
                                                </form>

                                                {{-- Reject --}}
                                                <button type="button"
                                                    @click="$dispatch('open-reject-modal', { id: {{ $request->id }}, material: '{{ $request->material->material_name }}', requester: '{{ $request->requester->name }}' })"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-rose-600 px-2.5 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-rose-500 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-1 dark:focus:ring-offset-gray-900">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                                                    {{ __('Reject') }}
                                                </button>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <button type="button"
                                                    @click="$dispatch('open-details-modal', {
                                                        request: {
                                                            id: {{ $request->id }},
                                                            date: '{{ $request->created_at->format('F d, Y \a\t h:i A') }}',
                                                            requester: {{ json_encode($request->requester->name) }},
                                                            project_code: {{ json_encode($request->project->project_code) }},
                                                            project_name: {{ json_encode($request->project->project_name) }},
                                                            material_name: {{ json_encode($request->material->material_name) }},
                                                            material_description: {{ json_encode($request->material->material_description ?? '') }},
                                                            quantity: {{ $request->quantity }},
                                                            unit: {{ json_encode($request->material->unitOfMeasure?->name ?? __('units')) }},
                                                            available_qty: {{ $request->material->quantity }},
                                                            description: {{ json_encode($request->description ?? '') }},
                                                            employee_file: {{ json_encode($request->employee_file ? asset('storage/' . $request->employee_file) : '') }},
                                                            status: {{ json_encode($request->status) }},
                                                            rejection_reason: {{ json_encode($request->rejection_reason ?? '') }},
                                                            approver_name: {{ json_encode($request->approver?->name ?? '') }},
                                                            approved_at: {{ json_encode($request->approved_at ? $request->approved_at->format('M d, Y \a\t h:i A') : '') }}
                                                        }
                                                    })"
                                                    class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-700 shadow-sm hover:bg-gray-50 active:scale-95 transition-all dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                                    {{ __('View') }}
                                                </button>
                                                @if ($request->isRejected() && $request->rejection_reason)
                                                    <span class="max-w-[120px] truncate text-xs text-rose-600 dark:text-rose-400" title="{{ $request->rejection_reason }}">
                                                        {{ Str::limit($request->rejection_reason, 20) }}
                                                    </span>
                                                @elseif ($request->isApproved())
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                                        {{ $request->approver?->name ?? '—' }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- DETAILS MODAL                                          --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div
        x-data="{ open: false, request: {}, showRejectForm: false }"
        x-on:open-details-modal.window="open = true; request = $event.detail.request; showRejectForm = false"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 overflow-y-auto"
        style="background: rgba(0,0,0,0.65); backdrop-filter: blur(4px);"
        @keydown.escape.window="open = false"
    >
        <div
            class="w-full max-w-2xl rounded-2xl bg-white dark:bg-gray-900 shadow-2xl overflow-hidden animate-slide-in-up"
            @click.outside="open = false"
        >
            {{-- Modal gradient header --}}
            <div class="eec-gradient px-6 py-5">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-base font-bold text-white">{{ __('Material Request Details') }}</h3>
                        <p class="mt-0.5 text-xs text-cyan-100/80">
                            {{ __('Submitted on') }} <span x-text="request.date"></span>
                        </p>
                    </div>
                    <button type="button" @click="open = false"
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10 text-white hover:bg-white/20 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal body --}}
            <div class="p-6 space-y-5 text-sm text-gray-700 dark:text-gray-300">

                {{-- Info Grid --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Requested By') }}</p>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-eec-500 to-teal-600 text-[10px] font-bold text-white">
                                <span x-text="request.requester ? request.requester[0].toUpperCase() : '?'"></span>
                            </span>
                            <span class="font-semibold text-gray-900 dark:text-white" x-text="request.requester"></span>
                        </div>
                    </div>
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Project / Site') }}</p>
                        <p class="font-semibold text-gray-900 dark:text-white">
                            <span x-text="request.project_code"></span>
                            <span class="font-normal text-gray-500 dark:text-gray-400"> — </span>
                            <span class="font-normal" x-text="request.project_name"></span>
                        </p>
                    </div>
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Material') }}</p>
                        <p class="font-semibold text-gray-900 dark:text-white" x-text="request.material_name"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-text="request.material_description" x-show="request.material_description"></p>
                    </div>
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mb-1">{{ __('Quantity & Stock') }}</p>
                        <div class="flex flex-wrap items-center gap-2 mt-1">
                            <span class="inline-flex items-center gap-1 rounded-full bg-eec-100 px-3 py-0.5 text-xs font-semibold text-eec-800 dark:bg-eec-900/30 dark:text-eec-300">
                                <span x-text="request.quantity"></span> <span x-text="request.unit"></span>
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                ({{ __('HO Stock:') }} <span class="font-semibold" x-text="request.available_qty"></span>)
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Justification --}}
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mb-2">{{ __('Justification') }}</p>
                    <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-4 text-sm text-gray-800 dark:text-gray-200 whitespace-pre-line min-h-[60px]"
                         x-text="request.description || '{{ __('No description provided.') }}'">
                    </div>
                </div>

                {{-- Employee File --}}
                <div x-show="request.employee_file">
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 mb-2">{{ __('Attached Employee List') }}</p>
                    <a :href="request.employee_file" target="_blank"
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-50 px-4 py-2.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition-colors dark:bg-indigo-900/30 dark:text-indigo-300">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        {{ __('Download Employee Excel File') }}
                    </a>
                </div>

                {{-- Status Info (Approved / Rejected) --}}
                <div x-show="request.status !== 'pending'">
                    <div x-show="request.status === 'approved'"
                         class="flex items-start gap-3 rounded-xl bg-emerald-50 border border-emerald-200 dark:bg-emerald-950/20 dark:border-emerald-800 p-4">
                        <svg class="h-5 w-5 shrink-0 text-emerald-600 dark:text-emerald-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">{{ __('Request Approved') }}</p>
                            <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-0.5">
                                {{ __('Approved by') }} <strong x-text="request.approver_name"></strong> {{ __('on') }} <span x-text="request.approved_at"></span>
                            </p>
                        </div>
                    </div>
                    <div x-show="request.status === 'rejected'"
                         class="flex items-start gap-3 rounded-xl bg-rose-50 border border-rose-200 dark:bg-rose-950/20 dark:border-rose-800 p-4">
                        <svg class="h-5 w-5 shrink-0 text-rose-600 dark:text-rose-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-rose-800 dark:text-rose-300">{{ __('Request Rejected') }}</p>
                            <p class="text-xs text-rose-700 dark:text-rose-400 mt-0.5">
                                {{ __('by') }} <strong x-text="request.approver_name"></strong> {{ __('on') }} <span x-text="request.approved_at"></span>
                            </p>
                            <p class="text-xs text-rose-800 dark:text-rose-300 mt-2 font-medium">
                                {{ __('Reason:') }} <span x-text="request.rejection_reason" class="font-normal"></span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Decision Panel (Pending only) --}}
                <div x-show="request.status === 'pending'" class="border-t border-gray-100 dark:border-gray-800 pt-4">
                    <div x-show="! showRejectForm" class="flex items-center justify-between gap-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Verify head office stock before approving.') }}</p>
                        <div class="flex gap-2 shrink-0">
                            <button type="button" @click="open = false"
                                class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all">
                                {{ __('Cancel') }}
                            </button>
                            <button type="button" @click="showRejectForm = true"
                                class="inline-flex items-center rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus:ring-2 focus:ring-rose-500 transition-all">
                                {{ __('Reject') }}
                            </button>
                            <form method="POST" :action="'{{ route('hse-officer.material-requests.approve', ['materialRequest' => '__ID__']) }}'.replace('__ID__', request.id)" class="inline">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('{{ __('Approve this material request? This will deduct quantity from head office stock.') }}')"
                                    class="inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus:ring-2 focus:ring-emerald-500 transition-all">
                                    {{ __('Approve') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Inline rejection form --}}
                    <div x-show="showRejectForm" x-cloak class="space-y-4">
                        <form method="POST" :action="'{{ route('hse-officer.material-requests.reject', ['materialRequest' => '__ID__']) }}'.replace('__ID__', request.id)">
                            @csrf
                            <div>
                                <label for="modal_rejection_reason" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">
                                    {{ __('Rejection Reason') }}
                                </label>
                                <textarea
                                    id="modal_rejection_reason"
                                    name="rejection_reason"
                                    rows="3"
                                    required
                                    placeholder="{{ __('Provide a clear reason for rejection...') }}"
                                    class="eec-input resize-none"
                                ></textarea>
                            </div>
                            <div class="flex justify-end gap-2 mt-3">
                                <button type="button" @click="showRejectForm = false"
                                    class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all">
                                    {{ __('Back') }}
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus:ring-2 focus:ring-rose-500 transition-all">
                                    {{ __('Reject Request') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- QUICK REJECT MODAL                                     --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div
        x-data="{ open: false, requestId: null, material: '', requester: '' }"
        x-on:open-reject-modal.window="open = true; requestId = $event.detail.id; material = $event.detail.material; requester = $event.detail.requester"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.65); backdrop-filter: blur(4px);"
        @keydown.escape.window="open = false"
    >
        <div class="w-full max-w-md rounded-2xl bg-white dark:bg-gray-900 shadow-2xl overflow-hidden animate-slide-in-up" @click.outside="open = false">

            {{-- Header --}}
            <div class="flex items-center gap-3 border-b border-rose-100 bg-rose-50 dark:border-rose-900/50 dark:bg-rose-950/30 px-6 py-4">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-rose-100 dark:bg-rose-900/50">
                    <svg class="h-5 w-5 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-rose-900 dark:text-rose-200">{{ __('Reject Material Request') }}</h3>
                    <p class="text-xs text-rose-600 dark:text-rose-400">
                        <span x-text="requester"></span> — <span x-text="material"></span>
                    </p>
                </div>
            </div>

            <div class="px-6 py-5">
                <form method="POST" :action="'{{ route('hse-officer.material-requests.reject', ['materialRequest' => '__ID__']) }}'.replace('__ID__', requestId)" class="space-y-4">
                    @csrf
                    <div>
                        <label for="rejection_reason" class="block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1.5">
                            {{ __('Rejection Reason') }}
                        </label>
                        <textarea
                            id="rejection_reason"
                            name="rejection_reason"
                            rows="4"
                            required
                            placeholder="{{ __('Provide a clear reason for rejection...') }}"
                            class="eec-input resize-none"
                        ></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-1">
                        <button type="button" @click="open = false"
                            class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus:ring-2 focus:ring-rose-500 transition-all">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                            {{ __('Reject Request') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>