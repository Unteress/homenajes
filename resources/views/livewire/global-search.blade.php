<?php

use Livewire\Volt\Component;
use App\Models\Deceased;

new class extends Component {
    public $search = '';

    public function with(): array
    {
        // Busca coincidencias por nombre, carga fotos para optimizar y limita a 5 resultados
        return [
            'results' => $this->search 
                ? Deceased::where('name', 'like', '%' . $this->search . '%')
                    ->with('photos') 
                    ->take(5)
                    ->get()
                : [],
        ];
    }
}; ?>

<div class="relative w-full max-w-sm md:max-w-md mx-4" x-data="{ open: false }" @click.outside="open = false">
    {{-- Campo de Input --}}
    <div class="relative group">
        <input 
            wire:model.live.debounce.300ms="search"
            @focus="open = true"
            @input="open = true"
            type="text" 
            placeholder="Buscar ser querido..." 
            class="w-full bg-gray-800 text-gray-200 border border-gray-700 rounded-full py-2 pl-10 pr-4 focus:outline-none focus:border-yellow-600 focus:ring-1 focus:ring-yellow-600 placeholder-gray-500 text-sm transition-all"
        >
        {{-- Ícono Lupa --}}
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 group-focus-within:text-yellow-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    {{-- Dropdown de Resultados --}}
    @if(strlen($search) > 0)
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden ring-1 ring-black/5">
            
            @if($results->count() > 0)
                <ul class="divide-y divide-gray-100">
                    @foreach($results as $result)
                        @php
                            // Lógica para obtener la foto de perfil o la primera disponible
                            $photo = $result->photos->where('type', 'profile')->first() ?? $result->photos->first();
                            $photoUrl = $photo ? asset('storage/' . $photo->path) : null;
                        @endphp
                        <li>
                            {{-- NOTA: Asegúrate de que tu ruta de perfil sea correcta. Aquí asumo '/profile/{id}' --}}
                            <a href="{{ url('/profile/' . $result->id) }}" wire:navigate class="flex items-center gap-3 px-4 py-3 hover:bg-yellow-50/50 transition cursor-pointer group">
                                
                                {{-- Avatar Circular --}}
                                <div class="w-10 h-10 rounded-full bg-gray-200 flex-shrink-0 overflow-hidden border border-gray-200 group-hover:border-yellow-400 transition">
                                    @if($photoUrl)
                                        <img src="{{ $photoUrl }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Texto --}}
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-800 group-hover:text-yellow-700 transition font-serif">{{ $result->name }}</span>
                                    <span class="text-[10px] uppercase tracking-wide text-gray-500">
                                        {{ $result->birth_date?->year }} - {{ $result->death_date->year }}
                                    </span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="p-4 text-center text-sm text-gray-500">
                    No encontramos coincidencias para "<span class="font-bold text-gray-700">{{ $search }}</span>"
                </div>
            @endif
        </div>
    @endif
</div>