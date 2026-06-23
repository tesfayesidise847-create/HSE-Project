<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Request Material') }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Submit a material request for HSE Officer approval') }}</p>
            </div>
            <a href="{{ route('site-officer.material-requests.index') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700">{{ __('My Requests') }}</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="rounded-xl bg-white p-8 shadow-sm dark:bg-gray-800" x-data="materialRequestForm()">
            <form method="POST" action="{{ route('site-officer.material-requests.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Project --}}
                <div>
                    <x-input-label for="project_id" :value="__('Project')" />
                    <select id="project_id" name="project_id" x-model="projectId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-indigo-400" required>
                        <option value="">-- {{ __('Select Project') }} --</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>
                                {{ $project->project_code }} — {{ $project->project_name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                </div>

                {{-- Material (Searchable) --}}
                <div class="relative" @click.outside="showMaterialDropdown = false">
                    <x-input-label for="material_search" :value="__('Material')" />
                    <input
                        id="material_search"
                        type="text"
                        x-model="materialSearch"
                        @input="onMaterialSearch()"
                        @focus="showMaterialDropdown = true"
                        autocomplete="off"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-indigo-400"
                        placeholder="{{ __('Search material name...') }}"
                        required
                    >
                    <input type="hidden" name="material_id" x-model="materialId" required>
                    <ul
                        x-show="showMaterialDropdown && filteredMaterials.length > 0"
                        x-cloak
                        class="absolute z-20 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900"
                    >
                        <template x-for="material in filteredMaterials" :key="material.id">
                            <li>
                                <button
                                    type="button"
                                    @click="selectMaterial(material)"
                                    class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-indigo-50 dark:text-gray-200 dark:hover:bg-gray-800"
                                    x-text="material.label"
                                ></button>
                            </li>
                        </template>
                    </ul>
                    <p x-show="showMaterialDropdown && materialSearch && filteredMaterials.length === 0" x-cloak class="absolute z-20 mt-1 w-full rounded-md border border-gray-200 bg-white px-4 py-2 text-sm text-gray-500 shadow-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
                        {{ __('No materials found.') }}
                    </p>
                    <p x-show="materialId" class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">{{ __('Material selected.') }}</p>
                    <x-input-error :messages="$errors->get('material_id')" class="mt-2" />
                </div>

                {{-- Quantity --}}
                <div>
                    <x-input-label for="quantity" :value="__('Quantity')" />
                    <x-text-input id="quantity" name="quantity" type="number" min="1" class="mt-1 block w-full" :value="old('quantity')" required placeholder="{{ __('Enter quantity') }}" />
                    <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                </div>

                {{-- Description / Justification --}}
                <div>
                    <x-input-label for="description" :value="__('Description / Justification')" />
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-indigo-400" required placeholder="{{ __('Describe why this material is needed and how it will be used...') }}">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                {{-- Employee File Upload --}}
                <div>
                    <x-input-label for="employee_file" :value="__('Employee List (Excel)')" />
                    <input id="employee_file" name="employee_file" type="file" accept=".xlsx,.xls,.csv" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-400 dark:file:bg-indigo-900/30 dark:file:text-indigo-300" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Optional: Upload an Excel file with employee names that this material will be distributed to. Accepted formats: .xlsx, .xls, .csv (max 2MB)') }}</p>
                    <x-input-error :messages="$errors->get('employee_file')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('site-officer.material-requests.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">{{ __('Cancel') }}</a>
                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        {{ __('Submit Request') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function materialRequestForm() {
            const allMaterials = @json($materialsForJs);

            return {
                projectId: @json(old('project_id', '')),
                materialId: @json(old('material_id', '')),
                materialSearch: '',
                showMaterialDropdown: false,

                get filteredMaterials() {
                    const query = this.materialSearch.toLowerCase().trim();

                    if (! query) {
                        return allMaterials.slice(0, 15);
                    }

                    return allMaterials.filter((material) => material.search.includes(query)).slice(0, 15);
                },

                onMaterialSearch() {
                    this.showMaterialDropdown = true;
                    this.materialId = '';
                },

                selectMaterial(material) {
                    this.materialId = material.id;
                    this.materialSearch = material.label;
                    this.showMaterialDropdown = false;
                },
            };
        }
    </script>
</x-app-layout>