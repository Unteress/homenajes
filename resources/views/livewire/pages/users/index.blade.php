<?php

use App\Models\User;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $roleFilter = ''; // Filtro por rol: '' (todos), 'admin', 'user'

    // Reseteamos paginación al buscar o filtrar
    public function updatedSearch() { $this->resetPage(); }
    public function updatedRoleFilter() { $this->resetPage(); }

    public function toggleRole(User $user)
    {
        if ($user->id === auth()->id()) {
            $this->dispatch('notify', 'No puedes cambiar tu propio rol.');
            return;
        }

        $user->role = $user->role === 'admin' ? 'user' : 'admin';
        $user->save();
        
        $this->dispatch('notify', "Rol de {$user->name} actualizado a " . strtoupper($user->role));
    }

    public function delete(User $user)
    {
         if ($user->id === auth()->id()) return;
        
        $user->delete();
        $this->dispatch('notify', 'Usuario eliminado.');
    }

    public function with(): array
    {
        $query = User::query()
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            });

        // Aplicar filtro de rol si no está vacío
        if (!empty($this->roleFilter)) {
            $query->where('role', $this->roleFilter);
        }

        return [
            'users' => $query->orderBy('created_at', 'desc')->paginate(10),
        ];
    }
}; ?>

<div>
    <div class="flex flex-col lg:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-2xl font-serif font-bold text-gray-900">Gestión de Usuarios</h2>
        
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            
            <select wire:model.live="roleFilter" 
                    class="rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 text-sm">
                <option value="">Todos los Roles</option>
                <option value="admin">Administradores</option>
                <option value="user">Usuarios Comunes</option>
            </select>

            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar usuario..." 
                   class="rounded-lg border-gray-300 focus:border-yellow-500 focus:ring-yellow-500 w-full sm:w-64 text-sm">
            
            <a href="{{ route('register.admin') }}" wire:navigate class="bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-black transition whitespace-nowrap flex items-center justify-center gap-2 shadow-md text-sm font-bold">
                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Crear admin
            </a>
        </div>
    </div>

    <div x-data="{ show: false, message: '' }" 
         @notify.window="show = true; message = $event.detail; setTimeout(() => show = false, 3000)"
         x-show="show" x-transition 
         class="fixed bottom-4 right-4 bg-gray-900 text-yellow-500 px-6 py-3 rounded-lg shadow-xl z-50 font-bold border-l-4 border-yellow-500">
        <span x-text="message"></span>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden border-t-4 border-yellow-600">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Contacto</th>
                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rol</th>
                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold border-2 {{ $user->role === 'admin' ? 'border-yellow-500' : 'border-gray-300' }}">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500 block sm:hidden">{{ $user->email }}</div> </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            <div class="text-sm text-gray-500">{{ $user->phone ?? 'Sin teléfono' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="toggleRole({{ $user->id }})" 
                                    @if($user->id === auth()->id()) disabled @endif
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full transition-colors cursor-pointer {{ $user->role === 'admin' ? 'bg-black text-yellow-500 border border-yellow-600' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                {{ $user->role === 'admin' ? 'ADMINISTRADOR' : 'USUARIO' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($user->id !== auth()->id())
                                <button wire:click="delete({{ $user->id }})" 
                                        wire:confirm="¿Seguro que desea eliminar a este usuario?"
                                        class="text-red-600 hover:text-red-900 bg-red-50 p-1.5 rounded hover:bg-red-100 transition"
                                        title="Eliminar Usuario">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            @else
                                <span class="text-gray-400 italic text-xs font-bold border border-gray-200 px-2 py-1 rounded">ACTUAL</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                            <p>No se encontraron usuarios con ese criterio.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 bg-gray-50 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</div>