<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 font-serif">
            {{ __('Borrar Cuenta') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente. Antes de eliminar su cuenta, descargue cualquier dato o información que desee conservar.') }}
        </p>
    </header>

    <div class="pt-2">
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="bg-red-600 hover:bg-red-700 focus:ring-red-500 shadow-md font-bold text-xs uppercase tracking-widest"
        >
            {{ __('Eliminar Cuenta') }}
        </x-danger-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="deleteUser" class="p-6 bg-white rounded-lg">
            
            <h2 class="text-xl font-bold text-gray-900 font-serif border-b-2 border-red-100 pb-2 mb-4">
                {{ __('¿Está seguro de que desea eliminar su cuenta?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Una vez que se elimine su cuenta, todos sus recursos y datos se eliminarán permanentemente. Por favor, introduzca su contraseña para confirmar que desea eliminar permanentemente su cuenta.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Contraseña') }}" class="sr-only" />

                <input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 border-gray-300 !bg-white text-gray-900 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm placeholder-gray-400"
                    placeholder="{{ __('Escriba su contraseña para confirmar') }}"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')" class="hover:bg-gray-100 border-gray-300">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="bg-red-600 hover:bg-red-700 shadow-lg border-b-2 border-red-800 ml-3">
                    {{ __('Sí, Eliminar Cuenta') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>