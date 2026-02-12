<?php

use Livewire\Volt\Component;
use App\Models\Setting;

new class extends Component {
    
    // Variables para el formulario
    public $max_photos_per_deceased;

    public function mount()
    {
        // Cargamos los valores de la BD. Si no existe, usamos 20 por defecto.
        $this->max_photos_per_deceased = Setting::get('deceased_max_photos', 20);
    }

    public function saveDeceasedSettings()
    {
        $this->validate([
            'max_photos_per_deceased' => 'required|integer|min:1|max:100',
        ]);

        // Guardar o Actualizar
        Setting::updateOrCreate(
            ['key' => 'deceased_max_photos'],
            ['value' => $this->max_photos_per_deceased]
        );

        // Mensaje de éxito
        session()->flash('message', 'Parámetros actualizados correctamente.');
    }
}; ?>

<div>
    <h2 class="text-2xl font-serif font-bold text-gray-900 mb-6">Configuración del Sistema</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm flex justify-between items-center">
            <span>{{ session('message') }}</span>
            <button wire:click="$refresh" class="text-green-700 hover:text-green-900 font-bold">X</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {{-- TARJETA 1: Parámetros de Fallecidos --}}
        <div class="bg-white rounded-lg shadow-lg border-t-4 border-gray-900 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Parámetros de Fallecidos
                </h3>
            </div>
            
            <div class="p-6 space-y-6">
                <form wire:submit="saveDeceasedSettings">
                    
                    {{-- Input: Máximo de Fotos --}}
                    <div>
                        <label for="max_photos" class="block text-sm font-medium text-gray-700 mb-1">Máximo de fotos permitidas por perfil</label>
                        <p class="text-xs text-gray-500 mb-2">Define cuántas imágenes se pueden subir a la galería de cada difunto.</p>
                        
                        <div class="flex items-center gap-4">
                            <input 
                                wire:model="max_photos_per_deceased" 
                                type="number" 
                                id="max_photos" 
                                min="1" 
                                max="100"
                                class="w-24 rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 sm:text-sm"
                            >
                            <span class="text-sm text-gray-500">fotos</span>
                        </div>
                        @error('max_photos_per_deceased') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-md text-sm font-bold uppercase tracking-wide transition-colors shadow-md">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- TARJETA 2: Parámetros de Homenajes (Placeholder para futuro) --}}
        <div class="bg-white rounded-lg shadow-lg border-t-4 border-yellow-600 overflow-hidden opacity-75">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    Parámetros de Homenajes
                </h3>
                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full font-bold">Próximamente</span>
            </div>
            
            <div class="p-6 flex flex-col items-center justify-center text-center space-y-4 min-h-[200px]">
                <div class="p-3 bg-yellow-50 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </div>
                <p class="text-gray-500 text-sm max-w-xs">
                    Proximamente...
                </p>
            </div>
        </div>

    </div>
</div>