<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EEC HSE') }} — {{ __('Sign In') }}</title>
        <meta name="description" content="EEC HSE Management System — Secure Login">

        <script>
            if (localStorage.getItem('theme') === 'dark' || (! localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .bg-grid-pattern {
                background-image:
                    linear-gradient(rgba(11,163,178,0.06) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(11,163,178,0.06) 1px, transparent 1px);
                background-size: 40px 40px;
            }
            .glow-card {
                box-shadow: 0 0 0 1px rgba(11,163,178,0.12),
                            0 20px 60px rgba(0,0,0,0.3),
                            0 0 80px rgba(11,163,178,0.06);
            }
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50%       { transform: translateY(-8px); }
            }
            .float-anim { animation: float 6s ease-in-out infinite; }
        </style>
    </head>
    <body class="font-sans antialiased">

        {{-- Full screen branded background --}}
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden bg-slate-100 px-4 py-10 transition-colors duration-300 dark:bg-[#061520] sm:px-6 lg:px-8">

            {{-- Animated background elements --}}
            <div class="bg-grid-pattern absolute inset-0 opacity-60 dark:opacity-100"></div>

            {{-- Theme toggle --}}
            <div class="absolute right-4 top-4 z-20 sm:right-6 sm:top-6" x-data="themeSwitcher()">
                <button
                    type="button"
                    @click="toggleTheme()"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white/90 text-slate-700 shadow-sm transition hover:bg-white focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-100 dark:border-white/10 dark:bg-white/10 dark:text-slate-200 dark:hover:bg-white/15 dark:focus:ring-offset-[#061520]"
                    :aria-label="isDark ? '{{ __('Switch to light mode') }}' : '{{ __('Switch to dark mode') }}'"
                >
                    <svg x-show="! isDark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21m9-9h-2.25M5.25 12H3m15.364-6.364-1.591 1.591M7.227 16.773l-1.591 1.591m12.728 0-1.591-1.591M7.227 7.227 5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                    <svg x-show="isDark" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0 1 18 15.75 9.75 9.75 0 0 1 8.25 6c0-1.33.266-2.598.748-3.752A9.753 9.753 0 0 0 3 11.25 9.75 9.75 0 0 0 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </button>
            </div>

            {{-- Radial glow blobs --}}
            <div class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-cyan-400/20 blur-3xl dark:bg-cyan-500/10"></div>
            <div class="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-teal-500/20 blur-3xl dark:bg-teal-600/10"></div>
            <div class="absolute left-1/2 top-1/2 h-[600px] w-[600px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/40 blur-3xl dark:bg-cyan-900/10"></div>

            {{-- Decorative floating shapes --}}
            <div class="absolute top-20 right-20 h-16 w-16 rounded-2xl border border-cyan-500/20 rotate-12 float-anim hidden xl:block"></div>
            <div class="absolute bottom-28 left-20 h-10 w-10 rounded-xl border border-teal-500/20 -rotate-6 float-anim hidden xl:block" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/3 right-32 h-6 w-6 rounded-full bg-cyan-500/20 float-anim hidden xl:block" style="animation-delay: 1s;"></div>

            {{-- Login Card --}}
            <div class="relative z-10 w-full max-w-md">
                <div class="glow-card overflow-hidden rounded-2xl border border-white bg-white/90 shadow-xl backdrop-blur-xl animate-slide-in-up dark:border-white/10 dark:bg-gray-900/80">

                    {{-- Card top gradient bar --}}
                    <div class="h-1 w-full eec-gradient-animated"></div>

                    {{-- Brand Header --}}
                    <div class="flex flex-col items-center border-b border-slate-200 px-8 pb-6 pt-8 text-center dark:border-white/10">
                        <a href="/" class="float-anim inline-block">
                            <x-application-logo class="h-16 w-auto object-contain drop-shadow-[0_0_16px_rgba(11,163,178,0.4)]" />
                        </a>
                        <h1 class="mt-4 text-xl font-bold tracking-tight text-slate-900 dark:text-white">
                            EEC HSE Management
                        </h1>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Ethiopian Engineering Corporation') }}
                        </p>
                    </div>

                    {{-- Form --}}
                    <div class="px-8 py-6">
                        {{ $slot }}
                    </div>

                    {{-- Footer --}}
                    <div class="px-8 pb-6 text-center">
                        <p class="text-xs text-slate-600">
                            © {{ date('Y') }} Ethiopian Engineering Corporation. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
