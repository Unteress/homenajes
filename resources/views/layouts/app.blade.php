<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Jardín de los Recuerdos') }}</title>
        
        <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600|playfair-display:700&display=swap" rel="stylesheet" />
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-100">
        
        <div class="flex h-screen overflow-hidden">

            <aside class="w-64 bg-gray-900 text-white flex flex-col shadow-2xl relative z-20 flex-shrink-0">
                
                <div wire:persist="main-sidebar-logo" class="h-20 flex items-center justify-center bg-black border-b-4 border-yellow-600 px-4">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3 group">
                        <img src="{{ asset('img/logo.png') }}" class="h-10 w-auto transition-transform duration-300 group-hover:scale-110" alt="Logo">
                        <div class="flex flex-col">
                            <span class="font-serif text-lg text-yellow-500 leading-tight">Camposanto</span>
                            <span class="font-serif text-xs text-white font-bold leading-tight">Jardín de los<br>Recuerdos</span>
                        </div>
                    </a>
                </div>

                <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto custom-scrollbar border-r-4 border-yellow-600">
                    
                    <a href="{{ route('dashboard') }}" 
                       wire:navigate 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-yellow-600 text-white shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-yellow-500' }}">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        <span class="font-medium">Panel Principal</span>
                    </a>

                    <a href="{{ route('deceased.index') }}" 
                       wire:navigate 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('deceased.*') ? 'bg-yellow-600 text-white shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-yellow-500' }}">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="font-medium">Memorias</span>
                    </a>

                    <a href="{{ route('users.index') }}" 
                        wire:navigate 
                        class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('users.*') ? 'bg-yellow-600 text-white shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-yellow-500' }}">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <span class="font-medium">Usuarios</span>
                    </a>

                    <a href="{{ route('settings.index') }}" 
                       wire:navigate 
                       class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('settings.*') ? 'bg-yellow-600 text-white shadow-md' : 'text-gray-400 hover:bg-gray-800 hover:text-yellow-500' }}">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="font-medium">Configuración</span>
                    </a>

                <!--<a href="#" 
                       class="flex items-center px-4 py-3 rounded-lg text-gray-400 hover:bg-gray-800 hover:text-yellow-500 transition-colors">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span class="font-medium">Homenajes</span>
                    </a>-->
                </nav>

                <div class="p-4 bg-gray-900 border-t border-gray-800 border-r-4 border-yellow-600">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm font-medium text-red-400 bg-gray-800 rounded-lg hover:bg-red-900 hover:text-white transition-colors group">
                            <svg class="w-5 h-5 mr-3 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </aside>

            <div class="flex-1 flex flex-col overflow-hidden relative bg-gray-100">
                
                <header class="bg-black shadow-lg border-b-4 border-yellow-600 h-20 flex items-center justify-between px-8 z-10 w-full shrink-0">
                    
                    <div class="flex items-center">
                        <a href="/" target="_blank" class="text-gray-300 hover:text-yellow-500 px-3 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-2 group">
                            <svg class="w-5 h-5 text-gray-500 group-hover:text-yellow-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            <span class="uppercase tracking-widest font-bold text-xs">Ver Sitio Público</span>
                        </a>
                    </div>

                    <div class="flex items-center gap-6">
                        
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                            <button @click="open = ! open" class="flex items-center gap-4 focus:outline-none transition group">
                                <div class="text-right hidden sm:block">
                                    <div class="text-sm font-bold text-yellow-500 font-serif tracking-wide">{{ Auth::user()->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase tracking-widest group-hover:text-gray-400 transition">Administrador</div>
                                </div>
                                
                                @if (Auth::user()->profile_photo_path)
                                    <img class="h-10 w-10 rounded-full object-cover border-2 border-yellow-400 shadow-md group-hover:scale-105 transition" src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" />
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-yellow-600 to-yellow-800 border-2 border-yellow-400 shadow-md flex items-center justify-center text-white font-serif font-bold text-lg group-hover:scale-105 transition">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif

                                <svg class="w-4 h-4 text-gray-500 group-hover:text-yellow-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-xl origin-top-right bg-white ring-1 ring-black ring-opacity-5 py-1 focus:outline-none border-t-4 border-yellow-600"
                                 style="display: none;">
                                
                                <a href="{{ route('profile') }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 transition-colors">
                                    Mi Perfil
                                </a>

                                

                                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors font-bold">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 scroll-smooth">
                    <div class="p-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>

        </div>
        
        @livewireScripts
    </body>
</html>