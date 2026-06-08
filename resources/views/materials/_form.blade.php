<div class="space-y-6">
    <div>
        <x-input-label for="material_name" :value="__('Material Name')" />
        <x-text-input id="material_name" name="material_name" type="text" class="mt-1 block w-full" :value="old('material_name', $material->material_name)" required autofocus />
        <x-input-error :messages="$errors->get('material_name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="material_description" :value="__('Material Description')" />
        <textarea id="material_description" name="material_description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>{{ old('material_description', $material->material_description) }}</textarea>
        <x-input-error :messages="$errors->get('material_description')" class="mt-2" />
    </div>

    @if (! $material->exists)
        <div>
            <x-input-label for="quantity" :value="__('Head Office Quantity')" />
            <x-text-input id="quantity" name="quantity" type="number" min="0" class="mt-1 block w-full" :value="old('quantity', $material->quantity ?? 0)" required />
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Initial stock balance at head office for this material.') }}</p>
            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
        </div>
    @endif
</div>
