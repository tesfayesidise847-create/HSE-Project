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
                    <select id="project_id" name="project_id" x-model="projectId" @change="resetEmployeeRows()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:focus:border-indigo-400" required>
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

                {{-- Employee Requests --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <x-input-label :value="__('Employee requests')" />
                        <button type="button" @click="addEmployeeRow()" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">{{ __('Add row') }}</button>
                    </div>

                    <template x-for="(row, index) in employeeRows" :key="row.id">
                        <div class="grid gap-4 rounded-lg border border-gray-200 p-4 dark:border-gray-700 sm:grid-cols-5" @click.outside="row.showEmployeeDropdown = false">
                            <div class="relative sm:col-span-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Employee name') }}</label>
                                <input
                                    type="text"
                                    x-model="row.search"
                                    @input="onEmployeeSearch(index)"
                                    @focus="row.showEmployeeDropdown = true"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300"
                                    placeholder="{{ __('Search or type employee name...') }}"
                                >

                                <input type="hidden" :name="'employee_requests[' + index + '][employee_id]'" :value="row.employee_id || ''">
                                <input type="hidden" :name="'employee_requests[' + index + '][employee_name]'" :value="row.employee_name || ''">

                                <ul
                                    x-show="row.showEmployeeDropdown && filteredEmployees(row).length > 0"
                                    x-cloak
                                    class="absolute z-20 mt-1 max-h-48 w-full overflow-auto rounded-md border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900"
                                >
                                    <template x-for="employee in filteredEmployees(row)" :key="employee.id">
                                        <li>
                                            <button
                                                type="button"
                                                @click="selectEmployee(index, employee)"
                                                class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-indigo-50 dark:text-gray-200 dark:hover:bg-gray-800"
                                                x-text="employee.label"
                                            ></button>
                                        </li>
                                    </template>
                                </ul>

                                <div x-show="row.showEmployeeDropdown && row.search.trim() && filteredEmployees(row).length === 0" x-cloak class="absolute z-20 mt-1 w-full rounded-md border border-gray-200 bg-white p-3 text-sm text-gray-600 shadow-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <p>{{ __('Employee not found in this project.') }}</p>
                                    <button type="button" @click="addTypedEmployee(index)" class="mt-2 inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500">
                                        {{ __('Add this employee') }}
                                    </button>
                                </div>
                            </div>

                            <div class="sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Quantity') }}</label>
                                <input type="number" min="1" :name="'employee_requests[' + index + '][quantity]'" x-model="row.quantity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" required>
                            </div>

                            <div class="flex items-end justify-end sm:col-span-1" x-show="employeeRows.length > 1">
                                <button type="button" @click="removeEmployeeRow(index)" class="text-sm font-medium text-red-600 hover:text-red-500">{{ __('Remove') }}</button>
                            </div>
                        </div>
                    </template>

                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total requested quantity:') }} <span class="font-semibold" x-text="totalQuantity"></span></p>
                    <x-input-error :messages="$errors->get('employee_requests')" class="mt-2" />
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
            const employeesByProject = @json($employeesByProjectForJs);

            return {
                projectId: @json(old('project_id', '')),
                materialId: @json(old('material_id', '')),
                materialSearch: '',
                showMaterialDropdown: false,
                employeeRows: [{
                    id: Date.now(),
                    employee_id: '',
                    employee_name: '',
                    search: '',
                    quantity: 1,
                    showEmployeeDropdown: false,
                }],

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
                get availableEmployees() {
                    return employeesByProject[this.projectId] || [];
                },
                filteredEmployees(row) {
                    const query = row.search.toLowerCase().trim();

                    if (! query) {
                        return this.availableEmployees.slice(0, 15);
                    }

                    return this.availableEmployees.filter((employee) => employee.search.includes(query)).slice(0, 15);
                },
                onEmployeeSearch(index) {
                    const row = this.employeeRows[index];
                    row.showEmployeeDropdown = true;
                    row.employee_id = '';
                    row.employee_name = '';
                },
                selectEmployee(index, employee) {
                    const row = this.employeeRows[index];
                    row.employee_id = employee.id;
                    row.employee_name = '';
                    row.search = employee.name;
                    row.showEmployeeDropdown = false;
                },
                addTypedEmployee(index) {
                    const row = this.employeeRows[index];
                    const typedName = row.search.trim();

                    if (! typedName) {
                        return;
                    }

                    row.employee_id = '';
                    row.employee_name = typedName;
                    row.search = typedName;
                    row.showEmployeeDropdown = false;
                },
                addEmployeeRow() {
                    this.employeeRows.push({
                        id: Date.now() + Math.random(),
                        employee_id: '',
                        employee_name: '',
                        search: '',
                        quantity: 1,
                        showEmployeeDropdown: false,
                    });
                },
                removeEmployeeRow(index) {
                    this.employeeRows.splice(index, 1);
                },
                get totalQuantity() {
                    return this.employeeRows.reduce((total, row) => total + Number(row.quantity || 0), 0);
                },
                resetEmployeeRows() {
                    this.employeeRows = [{
                        id: Date.now(),
                        employee_id: '',
                        employee_name: '',
                        search: '',
                        quantity: 1,
                        showEmployeeDropdown: false,
                    }];
                },
            };
        }
    </script>
</x-app-layout>