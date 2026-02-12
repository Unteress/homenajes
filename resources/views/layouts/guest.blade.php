<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Jardín de los Recuerdos') }}</title>
        
        <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|playfair-display:400,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @livewireStyles

        <style>
            .font-serif { font-family: 'Playfair Display', serif; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-900">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            
            <div wire:persist="guest-logo-container" class="mb-12 flex flex-col items-center">
                <a href="{{ url('/') }}" wire:navigate>
                    <img src="{{ asset('img/logo.png') }}" 
                         alt="Logo Jardín de los Recuerdos" 
                         class="w-96 h-auto drop-shadow-[0_20px_20px_rgba(234,179,8,0.2)] transition-transform hover:scale-105 duration-500">
                </a>
                </div>

            <div class="w-full sm:max-w-md mt-2 px-8 py-10 bg-white shadow-[0_35px_60px_-15px_rgba(0,0,0,0.5)] overflow-hidden sm:rounded-xl border-t-8 border-yellow-600">
                {{ $slot }}
            </div>
            
            <div class="mt-12 text-gray-500 text-xs tracking-widest uppercase">
                &copy; {{ date('Y') }} Camposanto Jardín de los Recuerdos
            </div>
        </div>

        @livewireScripts    
    </body>
</html>