<?php

use Livewire\Volt\Component;
use App\Models\Deceased;

new class extends Component {
    public $search = '';

    public function with(): array
    {
        return [
            'deceaseds' => Deceased::with('photos')
                ->where('is_public', true)
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->latest() // Los más recientes primero
                ->take(9)  // Límite de 9 registros
                ->get()
        ];
    }
}; ?>

<div>
    <div class="max-w-xl mx-auto mb-12 relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input wire:model.live.debounce.300ms="search" 
               type="text" 
               class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-full leading-5 bg-white placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 sm:text-lg shadow-md transition-all" 
               placeholder="Bucar homenajes o memorias de un ser querido..." 
               autofocus>
    </div>

    @if($deceaseds->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($deceaseds as $deceased)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden border-t-4 border-gray-900 hover:border-yellow-600 transition duration-300 group flex flex-col h-full">
                    
                    <div class="h-64 bg-gray-200 w-full overflow-hidden relative">
                        @php
                            $photo = $deceased->photos->where('type', 'profile')->first() ?? $deceased->photos->first();
                        @endphp

                        @if($photo)
                            <img src="{{ asset('storage/' . $photo->path) }}" 
                                 alt="{{ $deceased->name }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-300">
                                <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                        @endif
                        
                        <div class="absolute bottom-0 left-0 bg-yellow-600 text-white text-xs font-bold px-3 py-1 rounded-tr-lg">
                            {{ $deceased->birth_date ? $deceased->birth_date->format('Y') : '?' }} - {{ $deceased->death_date->format('Y') }}
                        </div>
                    </div>

                    <div class="p-6 flex-1 flex flex-col">
                        <h3 class="text-xl font-serif font-bold text-gray-900 mb-2 line-clamp-1" title="{{ $deceased->name }}">
                            {{ $deceased->name }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-grow italic">
                            {{ $deceased->biography ?: 'Sin biografía disponible.' }}
                        </p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                             <span class="text-xs text-gray-400 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ Str::limit($deceased->location, 15) }}
                             </span>

                            <a href="{{ route('public.profile', $deceased) }}" class="text-gray-900 hover:text-yellow-600 font-bold text-sm uppercase tracking-wide inline-flex items-center transition-colors">
                                Visitar <span class="ml-1">&rarr;</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">No se encontraron perfiles</h3>
            <p class="text-gray-500">Intente buscar con otro nombre o verifique la ortografía.</p>
        </div>
    @endif
</div>