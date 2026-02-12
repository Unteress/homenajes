<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
        $this->dispatch('notify', 'Contraseña actualizada correctamente.');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 font-serif">
            {{ __('Actualizar Contraseña') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Asegúrese de que su cuenta esté protegida usando una contraseña larga y segura.') }}
        </p>
    </header>

    <form wire:submit="updatePassword" class="mt-6 space-y-6">
        
        <div>
            <label for="update_password_current_password" class="block font-bold text-sm text-gray-700 font-serif">
                {{ __('Contraseña Actual') }}
            </label>
            <input wire:model="current_password" id="update_password_current_password" type="password" 
                   class="mt-1 block w-full border-gray-300 !bg-white text-gray-900 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" 
                   autocomplete="current-password" />
            <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="block font-bold text-sm text-gray-700 font-serif">
                {{ __('Nueva Contraseña') }}
            </label>
            <input wire:model="password" id="update_password_password" type="password" 
                   class="mt-1 block w-full border-gray-300 !bg-white text-gray-900 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" 
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block font-bold text-sm text-gray-700 font-serif">
                {{ __('Confirmar Nueva Contraseña') }}
            </label>
            <input wire:model="password_confirmation" id="update_password_password_confirmation" type="password" 
                   class="mt-1 block w-full border-gray-300 !bg-white text-gray-900 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" 
                   autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
            <button type="submit" class="px-6 py-2 bg-black hover:bg-gray-800 text-white font-bold rounded-md border-b-2 border-yellow-600 transition duration-150 ease-in-out shadow-lg">
                {{ __('Guardar Contraseña') }}
            </button>

            <x-action-message class="me-3" on="password-updated">
                <span class="text-green-600 font-bold">{{ __('¡Actualizada con éxito!') }}</span>
            </x-action-message>
        </div>
    </form>
</section>