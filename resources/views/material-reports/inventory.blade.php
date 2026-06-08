<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Head Office Material Inventory Report') }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Total materials created, quantities, and balances across the system') }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('material-reports.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">{{ __('Project Reports') }}</a>
                <a href="{{ route('material-quantities.index') }}" class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700">{{ __('Manage Quantities') }}</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 space-y-8">
        @include('partials.material-inventory-summary', ['summary' => $summary, 'isHeadOffice' => true])
        @include('partials.material-inventory-detail-table', ['materials' => $materials, 'isHeadOffice' => true])
    </div>
</x-app-layout>
