<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ __('Material Request Details') }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Review request details and approve or reject.') }}</p>
            </div>
            <a href="{{ route('hse-officer.material-requests.index') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600">
                {{ __('Back to Requests') }}
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl space-y-6">
        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/20">
                <ul class="list-disc pl-5 text-sm text-red-700 dark:text-red-400">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-lg border p-6 shadow-sm
            @if ($request->isPending())
                border-yellow-200 bg-yellow-50 dark:border-yellow-800 dark:bg-yellow-900/20
            @elseif ($request->isPartiallyApproved())
                border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-900/20
            @elseif ($request->isApproved())
                border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/20
            @else
                border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20
            @endif
        ">
            <h3 class="text-lg font-semibold
                @if ($request->isPending()) text-yellow-800 dark:text-yellow-300
                @elseif ($request->isPartiallyApproved()) text-blue-800 dark:text-blue-300
                @elseif ($request->isApproved()) text-green-800 dark:text-green-300
                @else text-red-800 dark:text-red-300
                @endif
            ">
                @if ($request->isPending())
                    {{ __('Request Pending Approval') }}
                @elseif ($request->isPartiallyApproved())
                    {{ __('Request Partially Approved') }}
                @elseif ($request->isApproved())
                    {{ __('Request Approved') }}
                @else
                    {{ __('Request Rejected') }}
                @endif
            </h3>
            <p class="mt-1 text-sm
                @if ($request->isPending()) text-yellow-700 dark:text-yellow-400
                @elseif ($request->isPartiallyApproved()) text-blue-700 dark:text-blue-400
                @elseif ($request->isApproved()) text-green-700 dark:text-green-400
                @else text-red-700 dark:text-red-400
                @endif
            ">
                @if ($request->isPending())
                    {{ __('Awaiting review. You can approve or reject below.') }}
                @elseif ($request->isPartiallyApproved())
                    {{ __('Some employees are already approved. You can approve the remaining employees below.') }}
                @elseif ($request->isApproved())
                    {{ __('Approved by') }} {{ $request->approver?->name ?? '-' }} {{ __('on') }} {{ $request->approved_at?->format('M d, Y \a\t h:i A') ?? '-' }}
                @else
                    {{ __('Rejected by') }} {{ $request->approver?->name ?? '-' }} {{ __('on') }} {{ $request->approved_at?->format('M d, Y \a\t h:i A') ?? '-' }}
                @endif
            </p>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-900">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Request Information') }}</h3>
            </div>
            <div class="p-6">
                <dl class="divide-y divide-gray-100 dark:divide-gray-800">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Request Date') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $request->created_at->format('F d, Y \a\t h:i A') }}</dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Requested By') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                            {{ $request->requester->name }}
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $request->requester->email }}</p>
                        </dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Project / Site') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                            <span class="font-medium">{{ $request->project->project_code }}</span> - {{ $request->project->project_name }}
                        </dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Material') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                            <span class="font-medium">{{ $request->material->material_name }}</span>
                            @if ($request->material->material_description)
                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $request->material->material_description }}</p>
                            @endif
                        </dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Quantity Requested') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
                            {{ $request->quantity }} {{ $request->material->unitOfMeasure?->name ?? __('units') }}
                            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">({{ __('Head Office Stock') }}: {{ $request->material->quantity }})</span>
                        </dd>
                    </div>
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Requested employees') }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">{{ $request->requestedEmployees->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-900">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Employee requests') }}</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">{{ __('Employee') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">{{ __('Quantity') }}</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @foreach ($request->requestedEmployees as $requestedEmployee)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $requestedEmployee->employee->fullName() }}</td>
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">{{ $requestedEmployee->quantity }}</td>
                                    <td class="px-4 py-2 text-sm">
                                        @if ($requestedEmployee->isApproved())
                                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">{{ __('Approved') }}</span>
                                        @else
                                            <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">{{ __('Pending') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($request->isRejected() && $request->rejection_reason)
            <div class="overflow-hidden rounded-lg border border-red-200 bg-red-50 shadow-sm dark:border-red-800 dark:bg-red-900/20">
                <div class="border-b border-red-200 px-6 py-4 dark:border-red-800">
                    <h3 class="text-base font-semibold text-red-800 dark:text-red-300">{{ __('Rejection Reason') }}</h3>
                </div>
                <div class="p-6 text-sm text-red-700 dark:text-red-400">
                    {{ $request->rejection_reason }}
                </div>
            </div>
        @endif

        @if ($request->isPending() || $request->isPartiallyApproved())
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-900">
                <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-gray-100">{{ __('Decision Panel') }}</h3>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <form method="POST" action="{{ route('hse-officer.material-requests.approve', $request) }}" class="w-full max-w-md space-y-3">
                        @csrf
                        <div>
                            <x-input-label for="approve_mode" :value="__('Approval Mode')" />
                            <select id="approve_mode" name="approve_mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                                <option value="all">{{ __('Approve all pending employees') }}</option>
                                <option value="selected">{{ __('Approve selected employees only') }}</option>
                            </select>
                        </div>
                        <div class="max-h-52 overflow-auto rounded-md border border-gray-200 p-3 dark:border-gray-700">
                            @foreach ($request->requestedEmployees->whereNull('approved_at') as $requestedEmployee)
                                <label class="mb-2 flex items-center gap-2 text-sm text-gray-700 last:mb-0 dark:text-gray-300">
                                    <input type="checkbox" name="employee_ids[]" value="{{ $requestedEmployee->employee_id }}" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                                    <span>{{ $requestedEmployee->employee->fullName() }} ({{ $requestedEmployee->quantity }})</span>
                                </label>
                            @endforeach
                        </div>
                        <button type="submit" class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500"
                            onclick="return confirm('{{ __('Approve selected employee request(s)? This will deduct quantity from head office stock and assign directly to employees.') }}')">
                            {{ __('Approve Request') }}
                        </button>
                    </form>

                    @if ($request->isPending())
                        <form method="POST" action="{{ route('hse-officer.material-requests.reject', $request) }}" class="w-full max-w-md space-y-3">
                            @csrf
                            <div>
                                <x-input-label for="rejection_reason" :value="__('Rejection Reason')" />
                                <textarea id="rejection_reason" name="rejection_reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100" required></textarea>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                                    {{ __('Reject Request') }}
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
