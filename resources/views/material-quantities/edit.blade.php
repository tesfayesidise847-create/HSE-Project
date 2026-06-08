<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Add Head Office Quantity') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-red-900/20 dark:text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-hidden shadow-sm sm:rounded-lg bg-white dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 rounded-lg bg-gray-50 p-4 text-sm text-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        <p><span class="font-semibold">{{ __('Material') }}:</span> {{ $material->material_name }}</p>
                        <p class="mt-1"><span class="font-semibold">{{ __('Current balance') }}:</span> {{ $material->quantity }}</p>
                    </div>

                    <form method="POST" action="{{ route('material-quantities.update', $material) }}">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="quantity_to_add" :value="__('Quantity to add')" />
                            <x-text-input id="quantity_to_add" name="quantity_to_add" type="number" min="1" class="mt-1 block w-full" :value="old('quantity_to_add', 1)" required autofocus />
                            <x-input-error :messages="$errors->get('quantity_to_add')" class="mt-2" />
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <x-primary-button>{{ __('Add to balance') }}</x-primary-button>
                            <a href="{{ route('material-quantities.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
