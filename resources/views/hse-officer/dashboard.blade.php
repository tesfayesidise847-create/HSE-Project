<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Head Office Dashboard') }}</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Overview of materials, projects, and assignments') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 space-y-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Stats Section --}}
        @include('partials.material-dashboard-report', ['stats' => $stats])

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Material Distribution Chart -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Material Distribution</h3>
                </div>
                <div class="p-6">
                    <canvas id="materialChart" class="w-full h-72"></canvas>
                </div>
            </div>

            <!-- Assignment Trend Chart -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
                <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recent Assignment Trend</h3>
                </div>
                <div class="p-6">
                    <canvas id="assignmentChart" class="w-full h-72"></canvas>
                </div>
            </div>
        </div>

        {{-- Recent Materials --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Recently Created Materials') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Latest additions to inventory') }}</p>
                </div>
                <a href="{{ route('materials.index') }}" 
                   class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1">
                    View All <span class="text-xs">→</span>
                </a>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse ($recentMaterials as $material)
                        <div x-data="{ open: false }" 
                             class="group border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all duration-200 bg-white dark:bg-gray-800 cursor-pointer h-full flex flex-col"
                             @click="open = !open">
                            
                            <div class="p-5 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 text-lg leading-tight pr-2">{{ $material->material_name }}</h4>
                                    <span class="px-3 py-1 rounded-xl text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 whitespace-nowrap">
                                        {{ $material->quantity }} {{ $material->unitOfMeasure?->name ?? 'units' }}
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 flex-1">
                                    {{ $material->material_description ?? 'No description available.' }}
                                </p>
                            </div>
                            
                            <div class="border-t border-gray-100 dark:border-gray-700 px-5 py-4 bg-gray-50 dark:bg-gray-900/50 flex items-center justify-between text-xs">
                                <span class="text-gray-500 dark:text-gray-400">
                                    {{ $material->created_at->format('M d, Y') }}
                                </span>
                                <a href="{{ route('materials.edit', $material) }}" 
                                   class="text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                                   @click.stop>Edit →</a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center">
                            <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mb-4">
                                📦
                            </div>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No materials found.') }}</p>
                        </div>
                    @endforelse
                </div>
                
                @if($recentMaterials->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $recentMaterials->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Project Assignments --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm dark:bg-gray-800 border border-gray-100 dark:border-gray-700">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-700 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Recent Project Assignments') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ __('Materials recently assigned to projects') }}</p>
                </div>
                <a href="{{ route('material-assignments.index') }}" 
                   class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1">
                    View All <span class="text-xs">→</span>
                </a>
            </div>
            
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($recentProjectAssignments as $assignment)
                    <div class="px-6 py-5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-lg flex-shrink-0">
                                    📦
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $assignment->material->material_name }}
                                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">× {{ $assignment->quantity }}</span>
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $assignment->project->project_code }} — {{ $assignment->project->project_name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right text-xs text-gray-500 dark:text-gray-400">
                            {{ $assignment->created_at->format('M d, Y') }}
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-16 text-center">
                        <p class="text-gray-500 dark:text-gray-400">{{ __('No project assignments yet.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @vite(['resources/js/dashboard-charts.js'])
</x-app-layout>