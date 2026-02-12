<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth; // Importante para detectar el rol
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Validation\ValidationException;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        
        try {
            $this->form->authenticate();
        } catch (ValidationException $e) {
            throw ValidationException::withMessages([
                'form.email' => 'Las credenciales introducidas no coinciden con nuestros registros.',
            ]);
        }

        Session::regenerate();

        // --- LÓGICA DE REDIRECCIÓN DINÁMICA ---
        if (Auth::user()->role === 'admin') {
            // Si es Admin -> Al Dashboard
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        } else {
            // Si es Usuario Normal -> Al Inicio (Welcome)
            $this->redirectIntended(default: '/', navigate: true);
        }
    }
}; ?>

<div>
    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold font-serif text-lg">¡Atención!</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="login" class="space-y-6">
        
        <div>
            <label for="email" class="block font-bold text-sm text-gray-700 font-serif">Correo Electrónico</label>
            <input wire:model="form.email" id="email" class="block mt-1 w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" type="email" name="email" required autofocus />
        </div>

        <div>
            <label for="password" class="block font-bold text-sm text-gray-700 font-serif">Contraseña</label>
            <input wire:model="form.password" id="password" class="block mt-1 w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" type="password" name="password" required />
        </div>

        <div class="block">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-yellow-600 shadow-sm focus:ring-yellow-500" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-medium">Recordarme en este equipo</span>
            </label>
        </div>

        <div class="flex items-center justify-end">
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-yellow-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 transition duration-150 ease-in-out shadow-lg">
                Iniciar Sesión
            </button>
        </div>
    </form>

    <div class="mt-8 pt-6 border-t border-gray-200 text-center">
        <p class="text-sm text-gray-600 mb-2">¿Desea crear un homenaje?</p>
        <a href="{{ route('register') }}" wire:navigate class="text-yellow-700 hover:text-yellow-900 font-bold text-sm uppercase tracking-wide transition-colors border-b-2 border-transparent hover:border-yellow-600">
            Crear Cuenta de Usuario
        </a>
    </div>
</div>