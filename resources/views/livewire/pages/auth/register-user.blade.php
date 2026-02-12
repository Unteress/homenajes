<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $phone = ''; // Campo obligatorio para usuarios
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
        $validated['role'] = 'user'; // Rol forzado a usuario común

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect('/', navigate: true);
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

    <form wire:submit="register" class="space-y-6">
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

        <div class="flex items-center justify-end pt-2">
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-yellow-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 transition duration-150 ease-in-out shadow-lg">
                Registrarse
            </button>
        </div>
    </form>

    <div class="mt-8 pt-6 border-t border-gray-200 text-center">
        <p class="text-sm text-gray-600 mb-2">¿Ya tiene una cuenta?</p>
        <a href="{{ route('login') }}" wire:navigate class="text-yellow-700 hover:text-yellow-900 font-bold text-sm uppercase tracking-wide transition-colors border-b-2 border-transparent hover:border-yellow-600">
            Iniciar Sesión
        </a>
    </div>
</div>  