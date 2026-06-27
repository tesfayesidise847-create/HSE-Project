<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-cyan-900/40 bg-[#0b1e2d] shadow-sm">

    {{-- Top gradient accent bar --}}
    <div class="h-0.5 w-full bg-gradient-to-r from-cyan-500 via-teal-400 to-cyan-500"></div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            {{-- Left: Logo --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex shrink-0 items-center gap-2.5">
                    <x-application-logo class="h-9 w-auto object-contain" />
                    <div class="hidden sm:block">
                        <p class="text-xs font-bold uppercase leading-none text-cyan-400">EEC</p>
                        <p class="mt-0.5 text-[10px] leading-none text-slate-400">HSE Management</p>
                    </div>
                </a>
            </div>

            {{-- Right: Desktop actions --}}
            <div class="hidden items-center gap-1.5 sm:flex sm:gap-2">

                {{-- Notification Bell --}}
                <x-notification-bell />

                {{-- Theme Toggle --}}
                <div x-data="themeSwitcher()">
                    <button
                        type="button"
                        @click="toggleTheme()"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-300 transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50"
                        :aria-label="isDark ? '{{ __('Switch to light mode') }}' : '{{ __('Switch to night mode') }}'"
                    >
                        <svg x-show="! isDark" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21m8.25-9h-2.25M6 12H3.75m14.03-5.78-1.59 1.59M7.81 16.19l-1.59 1.59m11.56 0-1.59-1.59M7.81 7.81 6.22 6.22M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        <svg x-show="isDark" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
                        </svg>
                    </button>
                </div>

                {{-- User Dropdown --}}
                <x-dropdown align="right" width="52">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 rounded-lg px-2.5 py-1.5 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50">
                            <span class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-cyan-500 to-teal-600 text-xs font-bold text-white">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden max-w-[120px] truncate sm:block">{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 shrink-0 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Signed in as') }}</p>
                            <p class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Mobile: Hamburger --}}
            <div class="flex items-center sm:hidden">
                <button
                    @click="open = ! open"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-slate-300 transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/50"
                    :aria-label="open ? '{{ __('Close menu') }}' : '{{ __('Open menu') }}'"
                >
                    <svg x-show="! open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Mobile menu --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="border-t border-cyan-900/40 bg-[#0b1e2d] sm:hidden"
    >
        {{-- User info --}}
        <div class="flex items-center gap-3 border-b border-cyan-900/30 px-4 py-4">
            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-cyan-500 to-teal-600 text-sm font-bold text-white">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </span>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-slate-200">{{ Auth::user()->name }}</p>
                <p class="truncate text-xs text-slate-500">{{ Auth::user()->email }}</p>
            </div>
        </div>

        {{-- Mobile nav links --}}
        <div class="space-y-1 px-3 py-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12 12 4.5 20.25 12M5.25 10.5V19.5h4.5v-5.25h4.5V19.5h4.5V10.5" />
                    </svg>
                    {{ __('Dashboard') }}
                </div>
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('notifications.index')">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                    {{ __('Notifications') }}
                </div>
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('profile.edit')">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0" />
                    </svg>
                    {{ __('Profile') }}
                </div>
            </x-responsive-nav-link>
        </div>

        {{-- Theme toggle + logout --}}
        <div class="border-t border-cyan-900/30 px-3 py-3 space-y-1">
            <div x-data="themeSwitcher()">
                <button
                    type="button"
                    @click="toggleTheme()"
                    class="flex w-full items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white"
                >
                    <svg x-show="! isDark" x-cloak class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21m8.25-9h-2.25M6 12H3.75m14.03-5.78-1.59 1.59M7.81 16.19l-1.59 1.59m11.56 0-1.59-1.59M7.81 7.81 6.22 6.22M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                    <svg x-show="isDark" x-cloak class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79Z" />
                    </svg>
                    <span x-text="isDark ? '{{ __('Switch to Light Mode') }}' : '{{ __('Switch to Night Mode') }}'"></span>
                </button>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    <div class="flex items-center gap-2 text-rose-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        {{ __('Log Out') }}
                    </div>
                </x-responsive-nav-link>
            </form>
        </div>
    </div>

</nav>