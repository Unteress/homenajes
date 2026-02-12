<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Jardín de los Recuerdos - Homenajes</title>
        
        <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600|playfair-display:400,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        
        <style>
            .font-serif { font-family: 'Playfair Display', serif; }
            html { scroll-behavior: smooth; }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-800 flex flex-col min-h-screen">

        <nav class="bg-black/90 backdrop-blur-md shadow-lg border-b-4 border-yellow-600 fixed w-full z-20 top-0 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20"> 
                    
                    {{-- Logotipo --}}
                    <div class="flex items-center">
                        <div wire:persist="public-nav-logo" class="flex-shrink-0 flex items-center gap-3">
                            <a href="/" wire:navigate class="flex items-center gap-3 group">
                                <img src="{{ asset('img/logo.png') }}" alt="Logo Jardín" class="h-12 w-auto transition-transform duration-300 group-hover:scale-105">
                                <div class="flex flex-col">
                                    <span class="font-serif text-xl text-yellow-500 leading-tight">Camposanto</span>
                                    <span class="font-serif text-lg text-white font-bold leading-tight hidden sm:block">Jardín de los Recuerdos</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Botón Página Principal --}}
                    <div class="flex items-center gap-4">
                        <a href="https://www.jardindelosrecuerdos.com/#consulta" class="text-sm font-medium text-gray-300 hover:text-yellow-500 transition uppercase tracking-widest flex items-center gap-2">
                            <span class="hidden sm:inline">Página Principal</span>
                            {{-- Ícono de Casa (Home) --}}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </a>
                    </div>
                    
                </div>
            </div>
        </nav>

        <div class="relative bg-gray-900 pt-32 pb-20 overflow-hidden">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl tracking-tight font-serif font-bold text-white sm:text-5xl md:text-6xl">
                    <span class="block">Honrando memorias,</span>
                    <span class="block text-yellow-500">celebrando vidas eternas.</span>
                </h1>
                <p class="mt-4 max-w-md mx-auto text-base text-gray-400 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Un espacio solemne dedicado al descanso eterno de sus seres queridos. Comparta recuerdos, encienda una luz y mantenga vivo su legado.
                </p>
            </div>
        </div>

        <div id="homenajes-recientes" class="bg-gray-100 py-16 flex-grow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-serif font-bold text-gray-900">En Memoria</h2>
                    <div class="w-24 h-1 bg-yellow-600 mx-auto mt-4 rounded"></div> 
                    <p class="mt-4 text-gray-600">Busque el perfil de su ser querido o explore los homenajes recientes.</p>
                </div>

                <livewire:welcome.recent-profiles />

            </div>
        </div>

        <footer class="bg-black border-t border-yellow-800 mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <p class="text-center text-gray-400 text-sm">
                    &copy; {{ date('Y') }} Camposanto Jardín de los Recuerdos. Todos los derechos reservados.
                </p>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>