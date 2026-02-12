<?php

use App\Models\Setting;
use App\Models\Deceased;
use App\Models\DeceasedPhoto;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads;

    public Deceased $deceased;
    public $uploads = []; 

    public function save() {
        $this->validate([
            'uploads.*' => 'image|max:10240', // 10MB
        ]);

        // 1. Obtener límite dinámico
        $limit = (int) Setting::get('deceased_max_photos', 20);
        $currentCount = $this->deceased->photos()->count();
        
        // 2. Validar antes de guardar
        if (($currentCount + count($this->uploads)) > $limit) {
            session()->flash('error', "No se pueden subir las fotos. El límite es de {$limit} y excederías esa cantidad.");
            return;
        }

        foreach ($this->uploads as $file) {
            $path = $file->store('deceased/' . $this->deceased->id, 'public');
            $this->deceased->photos()->create([
                'path' => $path,
                'type' => 'gallery'
            ]);
        }

        $this->uploads = []; 
        session()->flash('message', 'Imágenes subidas correctamente.');
    }

    // CORRECCIÓN: El hook debe llamarse igual que la propiedad (uploads)
    public function updatedUploads()
    {
        // 1. Obtener el límite de la BD
        $limit = (int) Setting::get('deceased_max_photos', 20);

        // 2. Contar fotos actuales + las nuevas
        $currentCount = $this->deceased->photos()->count();
        $newCount = count($this->uploads);

        if (($currentCount + $newCount) > $limit) {
            $this->addError('uploads', "El límite es de {$limit} fotos. Actualmente tienes {$currentCount} y estás intentando subir {$newCount} más.");
            $this->uploads = []; // Limpiar selección para obligar al usuario a elegir menos
            return;
        }

        // Validación de formato/peso
        $this->validate([
            'uploads.*' => 'image|max:10240', 
        ]);
    }

    public function removeFile($filename) {
        $this->uploads = collect($this->uploads)
            ->reject(fn($file) => $file->getFilename() === $filename)
            ->values()
            ->all();
    }

    public function setType($photoId, $type) {
        if (in_array($type, ['cover', 'profile'])) {
            $this->deceased->photos()->where('type', $type)->update(['type' => 'gallery']);
        }
        DeceasedPhoto::find($photoId)->update(['type' => $type]);
    }

    public function deletePhoto($photoId) {
        $photo = DeceasedPhoto::find($photoId);
        if (Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }
        $photo->delete();
    }

    // Enviamos el límite a la vista
    public function with(): array
    {
        return [
            'limit' => (int) Setting::get('deceased_max_photos', 20)
        ];
    }
}; ?>

<div class="space-y-8" x-data="{ 
    modalOpen: false, 
    activeImage: '',
    openModal(url) { 
        this.activeImage = url; 
        this.modalOpen = true; 
    }
}">
    
    <div class="flex items-center justify-between border-b-2 border-yellow-600 pb-4">
        <div>
            <h2 class="text-3xl font-serif font-bold text-gray-900">Álbum del Recuerdo</h2>
            <p class="text-gray-600 mt-1">
                Fallecido: <span class="font-bold text-gray-900">{{ $deceased->name }}</span> 
                {{-- CONTADOR DINÁMICO --}}
                <span class="ml-2 px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800 text-xs font-bold">
                    {{ $deceased->photos->count() }} / {{ $limit }} fotos
                </span>
            </p>
        </div>
        <a href="{{ route('deceased.index') }}" wire:navigate class="flex items-center text-sm font-bold text-gray-600 hover:text-yellow-600 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al Listado
        </a>
    </div>

    @if (session()->has('error')) 
        <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div> 
    @endif
    @if (session()->has('message')) 
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('message') }}
        </div> 
    @endif

    {{-- ZONA DE CARGA --}}
    <div 
        x-data="{ isDropping: false }"
        x-on:dragover.prevent="isDropping = true"
        x-on:dragleave.prevent="isDropping = false"
        x-on:drop.prevent="isDropping = false"
        :class="isDropping ? 'border-yellow-500 bg-yellow-50 ring-4 ring-yellow-100' : 'border-gray-300 bg-gray-50 hover:bg-gray-100 hover:border-yellow-400'"
        class="relative border-2 border-dashed rounded-xl p-10 transition-all duration-300 ease-in-out text-center group"
    >
        <input type="file" wire:model="uploads" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept="image/png, image/jpeg, image/jpg, image/webp">
        
        <div class="space-y-4 pointer-events-none">
            <div class="w-16 h-16 mx-auto bg-white rounded-full shadow-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-xl font-serif font-bold text-gray-800">Arrastra fotos aquí o examina</p>
                <p class="text-gray-500 text-sm mt-1">JPG, PNG, WebP (Máx 10MB)</p>
            </div>
        </div>

        {{-- Loading Overlay --}}
        <div wire:loading wire:target="uploads" class="absolute inset-0 z-20 bg-white/90 flex items-center justify-center rounded-xl">
            <span class="text-yellow-800 font-bold animate-pulse flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Procesando...
            </span>
        </div>
    </div>

    {{-- Errores de Validación (como superar el límite) --}}
    @error('uploads') 
        <div class="mt-2 text-red-600 font-bold text-center animate-pulse">
            {{ $message }}
        </div>
    @enderror

    @if ($uploads)
        <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-yellow-500 animate-fade-in-up">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-900">Listas para subir ({{ count($uploads) }})</h3>
                <button wire:click="$set('uploads', [])" class="text-red-500 text-sm hover:underline">Cancelar todo</button>
            </div>
            
            <div class="flex flex-wrap gap-4 max-h-60 overflow-y-auto p-2">
                @foreach ($uploads as $upload)
                    <div wire:key="{{ $upload->getFilename() }}" class="relative group w-24 h-24 rounded-lg overflow-hidden shadow-md border border-gray-200">
                        <img src="{{ $upload->temporaryUrl() }}" class="w-full h-full object-cover">
                        
                        <button 
                            type="button"
                            wire:click.prevent="removeFile('{{ $upload->getFilename() }}')" 
                            class="absolute top-1 right-1 bg-red-600 text-white p-1 rounded-full shadow-md hover:bg-red-700 transition-transform transform hover:scale-110 z-10 cursor-pointer"
                            title="Descartar esta imagen"
                        >
                            <svg wire:loading.remove wire:target="removeFile('{{ $upload->getFilename() }}')" class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <svg wire:loading wire:target="removeFile('{{ $upload->getFilename() }}')" class="animate-spin w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 flex justify-end">
                <button wire:click="save" wire:loading.attr="disabled" class="bg-gray-900 hover:bg-black text-white px-6 py-2 rounded-lg font-bold shadow-lg transition flex items-center transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="save">Subir Fotos al Servidor</span>
                    <span wire:loading wire:target="save" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Subiendo...
                    </span>
                </button>
            </div>
        </div>
    @endif

    {{-- GALERÍA EXISTENTE --}}
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @foreach ($deceased->photos as $photo)
            <div wire:key="photo-{{ $photo->id }}" class="relative group bg-white p-1.5 shadow-md rounded-lg border border-gray-100 hover:shadow-xl transition-all duration-300">
                
                <div class="aspect-square overflow-hidden rounded-md relative cursor-pointer"
                     @click="openModal('{{ asset('storage/' . $photo->path) }}')">
                    <img src="{{ asset('storage/' . $photo->path) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                    
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                    </div>
                </div>
                
                <div class="absolute top-3 left-3 flex flex-col gap-1 z-10 pointer-events-none">
                    @if($photo->type == 'cover')
                        <span class="bg-yellow-600 text-white text-[10px] px-2 py-0.5 rounded shadow-sm font-black uppercase tracking-wider flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                            Portada
                        </span>
                    @elseif($photo->type == 'profile')
                        <span class="bg-gray-900 text-white text-[10px] px-2 py-0.5 rounded shadow-sm font-black uppercase tracking-wider">Perfil</span>
                    @endif
                </div>

                <div class="mt-2 flex justify-between items-center px-1">
                    <div class="flex gap-2">
                        <button wire:click="setType({{ $photo->id }}, 'profile')" title="Foto de Perfil" class="{{ $photo->type == 'profile' ? 'text-gray-900' : 'text-gray-300 hover:text-gray-600' }} transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                        </button>
                        <button wire:click="setType({{ $photo->id }}, 'cover')" title="Foto de Portada" class="{{ $photo->type == 'cover' ? 'text-yellow-600' : 'text-gray-300 hover:text-yellow-600' }} transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                        </button>
                    </div>
                    <button wire:click="deletePhoto({{ $photo->id }})" wire:confirm="¿Eliminar esta foto permanentemente?" class="text-gray-300 hover:text-red-600 transition" title="Eliminar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    {{-- MODAL LIGTHBOX --}}
    <div 
        x-show="modalOpen" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm p-4"
        style="display: none;"
    >
        <button @click="modalOpen = false" class="absolute top-6 right-6 text-white/70 hover:text-white transition transform hover:scale-110">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <div class="relative max-w-5xl max-h-screen w-full flex justify-center" @click.outside="modalOpen = false">
            <img :src="activeImage" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl border-4 border-gray-900 object-contain">
        </div>
    </div>
</div>