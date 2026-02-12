<?php
use App\Models\Deceased;
use Livewire\Volt\Component;

new class extends Component {
    public ?Deceased $deceased = null;
    public $name = '', $birth_date = '', $death_date = '', $location = '', $biography = '', $is_public = true;

    public function mount(Deceased $deceased = null) {
        // SOLUCIÓN AL ERROR: Verificamos que no sea NULL antes de entrar
        if ($deceased && $deceased->exists) {
            $this->deceased = $deceased;
            $this->name = $deceased->name;
            $this->birth_date = $deceased->birth_date?->format('Y-m-d');
            $this->death_date = $deceased->death_date->format('Y-m-d');
            $this->location = $deceased->location;
            $this->biography = $deceased->biography;
            $this->is_public = $deceased->is_public;
        }
    }

    public function save() {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'death_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        if ($this->deceased && $this->deceased->exists) {
            $this->deceased->update($validated);
        } else {
            Deceased::create($validated);
        }
        return $this->redirectRoute('deceased.index', navigate: true);
    }
}; ?>

<div>
    <div class="flex items-center mb-6">
        <a href="{{ route('deceased.index') }}" wire:navigate class="text-gray-500 hover:text-yellow-600 mr-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-serif font-bold text-gray-900">{{ $deceased ? 'Editar Fallecido' : 'Registrar Fallecido' }}</h2>
    </div>

    <form wire:submit="save" class="bg-white shadow-xl rounded-lg overflow-hidden border-t-4 border-yellow-600">
        <div class="p-8 space-y-6">
            <div>
                <label class="block text-sm font-bold text-gray-700">Nombre Completo</label>
                <input type="text" wire:model="name" class="w-full border-gray-300 rounded-md focus:border-yellow-500 focus:ring-yellow-500">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700">Nacimiento</label>
                    <input type="date" wire:model="birth_date" class="w-full border-gray-300 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700">Defunción</label>
                    <input type="date" wire:model="death_date" class="w-full border-gray-300 rounded-md">
                    @error('death_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700">Ubicación</label>
                <input type="text" wire:model="location" class="w-full border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700">Biografía</label>
                <textarea wire:model="biography" rows="3" class="w-full border-gray-300 rounded-md"></textarea>
            </div>
        </div>
        <div class="bg-gray-50 px-8 py-4 flex justify-end space-x-4">
            <button type="submit" class="bg-gray-900 text-white px-6 py-2 rounded-lg font-bold border-b-2 border-yellow-600">Guardar</button>
        </div>
    </form>
</div>