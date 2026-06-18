<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Assign Materials to Employees') }}</h2>
    </x-slot>

    <div
        class="py-12"
        x-data="employeeAssignmentForm()"
        @keydown.escape.window="closeHistoryModal()"
    >
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg bg-white dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Search for an employee by name, then click their name to select them and view their previous material assignment history in a popup.') }}
                    </p>

                    <form method="POST" action="{{ route('site-officer.employee-assignments.store') }}">
                        @csrf

                        <div class="space-y-6">
                                <div class="sm:col-span-2">
                                    <x-input-label for="project_id" :value="__('Site (Project)')" />
                                    <select id="project_id" name="project_id" x-model="projectId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                                        <option value="">{{ __('Select project') }}</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->id }}" @selected(old('project_id', request('project_id')) == $project->id)>{{ $project->project_code }} — {{ $project->project_name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                                    <p x-show="projectId && availableEmployees.length === 0" x-cloak class="mt-2 text-sm text-amber-600 dark:text-amber-400">
                                        ⚠ {{ __('No employees are attached to this project yet.') }}
                                        <a href="#" @click.prevent="window.location.href = '{{ route('site-officer.projects.index') }}'" class="underline">{{ __('Go to Projects to add employees.') }}</a>
                                    </p>
                                </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ __('Assignments') }}</h3>
                                    <button type="button" @click="addRow()" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ __('Add row') }}</button>
                                </div>

                                <template x-for="(row, index) in rows" :key="row.id">
                                    <div class="grid gap-4 rounded-lg border border-gray-200 p-4 dark:border-gray-700 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" x-text="'{{ __('Material') }} ' + (index + 1)"></label>
                                            <select :name="'assignments[' + index + '][material_id]'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                                                <option value="">{{ __('Select material') }}</option>
                                                <template x-for="balance in availableMaterials" :key="balance.material_id">
                                                    <option :value="balance.material_id" x-text="balance.label"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Quantity') }}</label>
                                            <input type="number" min="1" :name="'assignments[' + index + '][quantity]'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                                        </div>
                                        <div class="relative" @click.outside="row.showEmployeeDropdown = false">
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Employee') }}</label>
                                            <input
                                                type="text"
                                                x-model="row.employeeSearch"
                                                @input="onEmployeeSearch(index)"
                                                @focus="row.showEmployeeDropdown = true"
                                                autocomplete="off"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                                placeholder="{{ __('Search employee name...') }}"
                                            >
                                            <input type="hidden" :name="'assignments[' + index + '][employee_id]'" :value="row.employee_id" required>
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
                                            <p x-show="row.showEmployeeDropdown && row.employeeSearch && filteredEmployees(row).length === 0" x-cloak class="absolute z-20 mt-1 w-full rounded-md border border-gray-200 bg-white px-4 py-2 text-sm text-gray-500 shadow-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
                                                {{ __('No employees found.') }}
                                            </p>
                                            <p x-show="row.employee_id" class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">{{ __('Employee selected.') }}</p>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Date') }}</label>
                                            <input type="date" :name="'assignments[' + index + '][assigned_date]'" :value="today" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                                        </div>
                                        <div class="sm:col-span-2" x-show="rows.length > 1">
                                            <button type="button" @click="removeRow(index)" class="text-sm text-red-600 hover:text-red-500">{{ __('Remove') }}</button>
                                        </div>
                                    </div>
                                </template>

                                <x-input-error :messages="$errors->get('assignments')" class="mt-2" />
                            </div>
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <x-primary-button>{{ __('Assign') }}</x-primary-button>
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('site-officer.partials.employee-history-popup')
    </div>

    @include('site-officer.partials.employee-history-script')

    <script>
        function employeeAssignmentForm() {
            const balancesByProject = @json($balancesForJs);
            const employeesByProject = @json($employeesByProjectForJs);

            return {
                ...employeeHistoryMixin(),
                projectId: @json($defaultProjectId),
                rows: [{
                    id: Date.now(),
                    employee_id: '',
                    employeeSearch: '',
                    showEmployeeDropdown: false,
                }],
                today: new Date().toISOString().slice(0, 10),
                get availableMaterials() {
                    return balancesByProject[this.projectId] || [];
                },
                get availableEmployees() {
                    return employeesByProject[this.projectId] || [];
                },
                newRow() {
                    return {
                        id: Date.now() + Math.random(),
                        employee_id: '',
                        employeeSearch: '',
                        showEmployeeDropdown: false,
                    };
                },
                filteredEmployees(row) {
                    const employees = this.availableEmployees;
                    const query = row.employeeSearch.toLowerCase().trim();

                    if (! query) {
                        return employees.slice(0, 15);
                    }

                    return employees.filter((employee) => employee.search.includes(query)).slice(0, 15);
                },
                onEmployeeSearch(index) {
                    this.rows[index].showEmployeeDropdown = true;
                    this.rows[index].employee_id = '';
                },
                selectEmployee(index, employee) {
                    this.rows[index].employee_id = employee.id;
                    this.rows[index].employeeSearch = employee.label;
                    this.rows[index].showEmployeeDropdown = false;
                    this.openHistoryModal(employee.id);
                },
                addRow() {
                    this.rows.push(this.newRow());
                },
                removeRow(index) {
                    this.rows.splice(index, 1);
                },
            };
        }
    </script>
</x-app-layout>
