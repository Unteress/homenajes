<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.blank')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $phone = ''; 
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'admin'; // Forzamos rol de administrador

        // Creamos el usuario
        event(new Registered($user = User::create($validated)));

        // --- CORRECCIÓN CLAVE ---
        // Eliminamos Auth::login($user); para no cambiar la sesión actual.
        
        // Enviamos mensaje de éxito
        session()->flash('message', 'Nuevo administrador registrado correctamente.');

        // Redirigimos al panel manteniendo tu sesión actual
        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <nav class="fixed top-0 left-0 w-full bg-black/90 backdrop-blur-md border-b-4 border-yellow-600 z-50 h-20 flex items-center justify-between px-4 sm:px-8 shadow-lg">
        
        <a href="/" wire:navigate class="flex items-center gap-3 group">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-10 w-auto transition-transform duration-300 group-hover:scale-110">
            <div class="flex flex-col">
                <span class="font-serif text-lg text-yellow-500 leading-tight">Camposanto</span>
                <span class="font-serif text-xs text-white font-bold leading-tight hidden sm:block">Jardín de los Recuerdos</span>
            </div>
        </a>

        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 text-gray-300 hover:text-yellow-500 transition-colors group">
            <span class="hidden sm:inline text-xs font-bold uppercase tracking-widest">Volver al Panel</span>
            <div class="p-2 bg-gray-800 rounded-full group-hover:bg-yellow-600 group-hover:text-black transition-colors border border-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            </div>
        </a>
    </nav>

    <div class="min-h-screen flex flex-col justify-center items-center pt-24 pb-10 px-4">
        
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-serif font-bold text-gray-900">Registrar Administrador</h2>
            <p class="text-sm text-gray-500 mt-2">Crear una nueva cuenta con privilegios de gestión.</p>
        </div>

        @if ($errors->any())
            <div class="w-full sm:max-w-md mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm" role="alert">
                <strong class="font-bold font-serif text-lg">¡Atención!</strong>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="w-full sm:max-w-md bg-white border border-gray-200 shadow-xl rounded-lg overflow-hidden border-t-4 border-t-yellow-600 p-8">
            <form wire:submit="register" class="space-y-5">
                <div>
                    <label for="name" class="block font-bold text-sm text-gray-700 font-serif">Nombre Completo</label>
                    <input wire:model="name" id="name" class="block mt-1 w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" type="text" name="name" required autofocus autocomplete="name" />
                </div>

                <div>
                    <label for="email" class="block font-bold text-sm text-gray-700 font-serif">Correo Electrónico</label>
                    <input wire:model="email" id="email" class="block mt-1 w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" type="email" name="email" required autocomplete="username" />
                </div>

                <div>
                    <label for="phone" class="block font-bold text-sm text-gray-700 font-serif">Teléfono / Celular</label>
                    <input wire:model="phone" id="phone" class="block mt-1 w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" type="text" name="phone" required placeholder="Ej. 099 123 4567" />
                </div>

                <div>
                    <label for="password" class="block font-bold text-sm text-gray-700 font-serif">Contraseña</label>
                    <input wire:model="password" id="password" class="block mt-1 w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div>
                    <label for="password_confirmation" class="block font-bold text-sm text-gray-700 font-serif">Confirmar Contraseña</label>
                    <input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 rounded-md shadow-sm" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="flex items-center justify-end pt-4">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-yellow-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 transition duration-150 ease-in-out shadow-lg transform hover:-translate-y-0.5">
                        Registrar Administrador
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>