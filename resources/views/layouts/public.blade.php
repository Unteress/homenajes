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
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50 flex flex-col min-h-screen">
        
        {{-- Navbar Fijo --}}
        <nav class="bg-black/95 backdrop-blur-md shadow-lg border-b-4 border-yellow-600 fixed w-full z-40 top-0 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20"> 
                    
                    {{-- Logo Izquierda --}}
                    <div class="flex items-center flex-shrink-0">
                        <a href="/" wire:navigate class="flex items-center gap-3 group">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo Jardín" class="h-10 md:h-12 w-auto transition-transform duration-300 group-hover:scale-105">
                            <div class="flex flex-col">
                                <span class="font-serif text-lg md:text-xl text-yellow-500 leading-tight">Camposanto</span>
                                <span class="font-serif text-sm md:text-lg text-white font-bold leading-tight hidden md:block">Jardín de los Recuerdos</span>
                            </div>
                        </a>
                    </div>

                    {{-- Buscador Central (Componente Nuevo) --}}
                    <div class="flex-grow flex justify-center mx-4">
                        <livewire:global-search />
                    </div>
                    
                    {{-- Botón Derecha --}}
                    <div class="flex items-center flex-shrink-0">
                        <a href="https://www.jardindelosrecuerdos.com/#consulta" class="text-xs md:text-sm font-medium text-gray-300 hover:text-yellow-500 transition uppercase tracking-widest flex items-center gap-2">
                            <span class="hidden lg:inline">Página Principal</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </a>
                    </div>

                </div>
            </div>
        </nav>

        {{-- Contenido Principal --}}
        <main class="pt-20 flex-grow w-full">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="bg-black border-t border-yellow-800 mt-auto z-50 relative">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-400 text-sm">
                    &copy; {{ date('Y') }} Camposanto Jardín de los Recuerdos. Todos los derechos reservados.
                </p>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>