<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EEC HSE') }} — {{ __('Sign In') }}</title>
        <meta name="description" content="EEC HSE Management System — Secure Login">

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
        <div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-[#061520]">

            {{-- Animated background elements --}}
            <div class="bg-grid-pattern absolute inset-0 opacity-100"></div>

            {{-- Radial glow blobs --}}
            <div class="absolute -top-32 -left-32 h-96 w-96 rounded-full bg-cyan-500/10 blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 h-96 w-96 rounded-full bg-teal-600/10 blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-[600px] w-[600px] rounded-full bg-cyan-900/10 blur-3xl"></div>

            {{-- Decorative floating shapes --}}
            <div class="absolute top-20 right-20 h-16 w-16 rounded-2xl border border-cyan-500/20 rotate-12 float-anim hidden xl:block"></div>
            <div class="absolute bottom-28 left-20 h-10 w-10 rounded-xl border border-teal-500/20 -rotate-6 float-anim hidden xl:block" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/3 right-32 h-6 w-6 rounded-full bg-cyan-500/20 float-anim hidden xl:block" style="animation-delay: 1s;"></div>

            {{-- Login Card --}}
            <div class="relative z-10 w-full max-w-md px-4">
                <div class="glow-card rounded-2xl bg-gray-900/80 backdrop-blur-xl border border-white/8 overflow-hidden animate-slide-in-up">

                    {{-- Card top gradient bar --}}
                    <div class="h-1 w-full eec-gradient-animated"></div>

                    {{-- Brand Header --}}
                    <div class="flex flex-col items-center px-8 pt-8 pb-6 text-center border-b border-white/6">
                        <a href="/" class="float-anim inline-block">
                            <x-application-logo class="h-16 w-auto object-contain drop-shadow-[0_0_16px_rgba(11,163,178,0.4)]" />
                        </a>
                        <h1 class="mt-4 text-xl font-bold text-white tracking-tight">
                            EEC HSE Management
                        </h1>
                        <p class="mt-1 text-sm text-slate-400">
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
