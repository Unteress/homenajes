<?php

use App\Models\Deceased;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $dateFilter = '';

    public function updatedSearch() { $this->resetPage(); }
    public function updatedDateFilter() { $this->resetPage(); }

    // Usamos is_public
    public function togglePublic($id)
    {
        $deceased = Deceased::find($id);
        if ($deceased) {
            $deceased->is_public = !$deceased->is_public;
            $deceased->save();
        }
    }

    public function delete($id)
    {
        $deceased = Deceased::with('photos')->find($id);

        if ($deceased) {
            foreach ($deceased->photos as $photo) {
                if (Storage::disk('public')->exists($photo->path)) {
                    Storage::disk('public')->delete($photo->path);
                }
            }
            $deceased->delete();
            session()->flash('message', 'Registro eliminado correctamente.');
        }
    }

    public function with(): array
    {
        $query = Deceased::with('photos')->latest();

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->dateFilter)) {
            $query->whereDate('created_at', $this->dateFilter);
        }

        return [
            'deceaseds' => $query->paginate(10),
        ];
    }
}; ?>

<div> 
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-serif font-bold text-gray-900">Gestión de Fallecidos</h2>
        
        <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto items-center">
            
            <div class="relative w-full md:w-auto">
                <input wire:model.live="dateFilter" type="date" class="block w-full md:w-auto rounded-lg border-gray-300 bg-white shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50 transition duration-150 ease-in-out sm:text-sm text-gray-600" title="Filtrar por fecha de registro">
            </div>

            <div class="relative w-full md:w-auto">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="pl-10 block w-full md:w-64 rounded-lg border-gray-300 bg-white shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-500 focus:ring-opacity-50 transition duration-150 ease-in-out sm:text-sm" placeholder="Buscar por nombre...">
            </div>

            <a href="{{ route('deceased.create') }}" wire:navigate class="w-full md:w-auto bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg shadow transition-colors flex items-center justify-center whitespace-nowrap">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Registro
            </a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow-sm flex justify-between items-center" role="alert">
            <span>{{ session('message') }}</span>
            <button wire:click="$refresh" class="text-green-700 hover:text-green-900"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden border-t-4 border-gray-900">
        <div wire:loading wire:target="search, dateFilter, delete, togglePublic" class="w-full bg-yellow-50 p-2 text-center text-yellow-800 text-sm font-semibold">
            Procesando...
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-900 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Perfil y Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider hidden md:table-cell">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider hidden sm:table-cell">Ubicación</th>
                    <th class="px-6 py-3 text-right text-xs font-bold uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($deceaseds as $item)
                    <tr class="hover:bg-gray-50 transition-colors group {{ !$item->is_public ? 'bg-gray-50 opacity-60' : '' }}">
                        
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @php $photo = $item->photos->where('type', 'profile')->first() ?? $item->photos->first(); @endphp
                                    @if($photo)
                                        <img class="h-10 w-10 rounded-full object-cover border-2 border-yellow-600 shadow-sm" src="{{ asset('storage/' . $photo->path) }}" alt="{{ $item->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 border-2 border-gray-300 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900 group-hover:text-yellow-700 transition-colors">
                                        {{ $item->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $item->birth_date?->format('Y') ?? '?' }} - {{ $item->death_date->format('Y') }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap hidden md:table-cell">
                             @if($item->is_public)
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                    Público
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                    Oculto
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-yellow-100 text-yellow-800 border border-yellow-200">
                                {{Str::limit($item->location ?? 'No asignada', 20) }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                {{-- 2. BOTÓN IR AL PERFIL (NUEVO) --}}
                                <a href="{{ route('public.profile', $item) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded" title="Ver Perfil Público">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>

                                {{-- 1. BOTÓN OCULTAR/MOSTRAR --}}
                                <button wire:click="togglePublic({{ $item->id }})" 
                                        class="p-1 rounded transition-colors {{ $item->is_public ? 'text-gray-400 hover:text-red-600 hover:bg-red-50' : 'text-green-600 hover:text-green-800 hover:bg-green-50' }}" 
                                        title="{{ $item->is_public ? 'Ocultar Perfil' : 'Hacer Público' }}">
                                    @if($item->is_public)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    @endif
                                </button>

                                

                                {{-- 3. BOTÓN GALERÍA --}}
                                <a href="{{ route('deceased.gallery', $item) }}" wire:navigate class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded" title="Fotos">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </a>

                                {{-- 4. BOTÓN EDITAR --}}
                                <a href="{{ route('deceased.edit', $item) }}" wire:navigate class="text-yellow-600 hover:text-yellow-900 p-1 hover:bg-yellow-50 rounded" title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                                {{-- 5. BOTÓN ELIMINAR --}}
                                <button wire:click="delete({{ $item->id }})" wire:confirm="¿Eliminar?" class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded" title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                            No se encontraron registros.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 bg-gray-50 border-t border-gray-200">{{ $deceaseds->links() }}</div>
    </div>
</div>