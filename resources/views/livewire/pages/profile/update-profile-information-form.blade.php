<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = ''; 
    public $photo; 

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'max:1024'], 
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($this->photo) {
            $user->profile_photo_path = $this->photo->store('profile-photos', 'public');
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
        $this->dispatch('notify', 'Perfil actualizado correctamente'); 
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 font-serif">
            {{ __('Información Personal') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Actualice la información de su cuenta y su foto de perfil.
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        
        <div class="flex items-center gap-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="shrink-0 relative">
                @if ($photo)
                    <img src="{{ $photo->temporaryUrl() }}" class="h-24 w-24 object-cover rounded-full border-4 border-yellow-500 shadow-md">
                @elseif (Auth::user()->profile_photo_path)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" class="h-24 w-24 object-cover rounded-full border-4 border-white shadow-sm">
                @else
                    <div class="h-24 w-24 rounded-full bg-black text-yellow-500 flex items-center justify-center font-serif font-bold text-3xl border-4 border-yellow-600 shadow-md">
                        {{ substr($name, 0, 1) }}
                    </div>
                @endif
            </div>

            <div class="flex-1">
                <label class="block text-sm font-bold text-gray-900 font-serif mb-2">Cambiar Foto de Perfil</label>
                <input type="file" wire:model="photo" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-xs file:font-bold
                    file:bg-yellow-600 file:text-white
                    hover:file:bg-yellow-700
                    file:cursor-pointer cursor-pointer
                    focus:outline-none
                "/>
                <p class="mt-1 text-xs text-gray-500">Formatos: JPG, PNG. Máximo 1MB.</p>
                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
            </div>
        </div>hdfhhsdgsdliuvdbgvxdlhb

        <div>
            <label for="name" class="block font-bold text-sm text-gray-700 font-serif">Nombre Completo</label>
            <input wire:model="name" id="name" type="text" 
                   class="mt-1 block w-full border-gray-300 !bg-white text-gray-900 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" 
                   required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block font-bold text-sm text-gray-700 font-serif">Correo Electrónico</label>
            <input wire:model="email" id="email" type="email" 
                   class="mt-1 block w-full border-gray-300 !bg-white text-gray-900 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" 
                   required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <label for="phone" class="block font-bold text-sm text-gray-700 font-serif">Teléfono / Celular</label>
            <input wire:model="phone" id="phone" type="text" 
                   class="mt-1 block w-full border-gray-300 !bg-white text-gray-900 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" 
                   required placeholder="Ej: 099 123 4567" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" class="px-6 py-2 bg-black hover:bg-gray-800 text-white font-bold rounded-md border-b-4 border-yellow-600 transition duration-150 ease-in-out shadow-lg">
                {{ __('Guardar Cambios') }}
            </button>

            <x-action-message class="me-3" on="profile-updated">
                <span class="text-green-600 font-bold">{{ __('¡Guardado con éxito!') }}</span>
            </x-action-message>
        </div>
    </form>
</section>