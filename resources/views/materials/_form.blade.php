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

    <div>
        <x-input-label for="unit_of_measure_id" :value="__('Unit of Measure')" />
        <select id="unit_of_measure_id" name="unit_of_measure_id" class="mt-1 block w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
            <option value="">{{ __('Select a unit') }}</option>
            @foreach($unitOfMeasures as $unitOfMeasure)
                <option value="{{ $unitOfMeasure->id }}" @selected(old('unit_of_measure_id', $material->unit_of_measure_id) == $unitOfMeasure->id)>{{ $unitOfMeasure->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('unit_of_measure_id')" class="mt-2" />
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Choose the unit used to track this material quantity.') }}</p>
    </div>

    <div>
        <x-input-label for="quantity" :value="__('Head Office Quantity')" />
        <x-text-input id="quantity" name="quantity" type="number" min="0" class="mt-1 block w-full" :value="old('quantity', $material->quantity ?? 0)" required />
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('Stock balance at head office for this material.') }}</p>
        <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
    </div>
</div>
