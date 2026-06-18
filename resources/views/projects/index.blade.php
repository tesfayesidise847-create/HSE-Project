<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Projects') }}</h2>
                @unless ($isAdmin)
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('View and manage projects') }}</p>
                @endunless
            </div>
            @if ($isAdmin)
                <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-700 dark:hover:bg-blue-800 dark:focus:ring-offset-gray-900">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Create Project') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden shadow-sm sm:rounded-lg bg-white dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">{{ __('Project Name') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">{{ __('Project Code') }}</th>
                                    @if ($isAdmin)
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">{{ __('Site Officer') }}</th>
                                    @endif
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($projects as $project)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="block font-medium text-gray-900 dark:text-gray-100">{{ $project->project_name }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">{{ $project->project_code }}</span>
                                        </td>
                                        @if ($isAdmin)
                                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                                {{ $project->siteOfficer?->name ?? '—' }}
                                            </td>
                                        @endif
                                        <td class="whitespace-nowrap px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                @if ($isAdmin)
                                                    <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-100 px-3 py-2 text-xs font-medium text-blue-700 hover:bg-blue-200 transition dark:bg-blue-900/30 dark:text-blue-300 dark:hover:bg-blue-900/50">
                                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M2.695 14.762c-.192.461-.35 1.08.032 1.463.382.382 1.002.224 1.463.032l.512-.204c.393-.157.86-.358 1.4-.748a.5.5 0 00-.372-.820c-.119 0-.22-.182-.393-.324h-.001a.5.5 0 00-.614.09l-.204.153c-.466.35-.976.556-1.423.7l-.512.204zM14.5 1a2.5 2.5 0 0 0-2.121 4.12l-7.757 7.757a2 2 0 00-.439 2.384l.328.82-.820.328a2 2 0 00-.384.439l-7.757 7.757A2.5 2.5 0 1 0 2.99 19.656l7.757-7.757a2 2 0 00.439-.384l.82-.328-.328-.82a2 2 0 00.384-2.384l7.757-7.757A2.5 2.5 0 0 0 14.5 1zm0 2a.5.5 0 1 1 0 1 .5.5 0 0 1 0-1z" />
                                                        </svg>
                                                        {{ __('Edit') }}
                                                    </a>
                                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this project?') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-red-100 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-200 transition dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50">
                                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                            </svg>
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('site-officer.projects.show', $project) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-100 px-3 py-2 text-xs font-medium text-emerald-700 hover:bg-emerald-200 transition dark:bg-emerald-900/30 dark:text-emerald-300 dark:hover:bg-emerald-900/50">
                                                        <svg class="h-4 w-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ __('View') }}
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="@if ($isAdmin) 4 @else 3 @endif" class="px-6 py-12">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="h-12 w-12 text-gray-400 dark:text-gray-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0018 4.5h-2.25A2.25 2.25 0 0013.5 6.75v10.5A2.25 2.25 0 0015.75 19.5z" />
                                                </svg>
                                                <p class="text-center text-sm font-medium text-gray-600 dark:text-gray-400">
                                                    @if ($isAdmin)
                                                        {{ __('No projects created yet.') }}
                                                    @else
                                                        {{ __('No projects assigned to you.') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($projects->hasPages())
                        <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
                            <div class="mb-4 flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                                <span>{{ __('Showing') }} <span class="font-medium">{{ $projects->firstItem() }}</span> {{ __('to') }} <span class="font-medium">{{ $projects->lastItem() }}</span> {{ __('of') }} <span class="font-medium">{{ $projects->total() }}</span> {{ __('projects') }}</span>
                            </div>
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
