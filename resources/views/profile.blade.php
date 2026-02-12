    @php
        // Definimos qué archivo de diseño usar según el rol
        $layout = Auth::user()->role === 'admin' ? 'layouts.app' : 'layouts.public';
    @endphp

    @component($layout)

        @if(Auth::user()->role !== 'admin')
            <div class="bg-black border-b-4 border-yellow-600 pt-10 pb-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl font-serif font-bold text-white">Mi Perfil</h2>
                    <p class="text-gray-400 text-sm mt-1">Gestione sus datos personales y seguridad.</p>
                </div>
            </div>
        @else
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
                <h2 class="font-serif font-bold text-2xl text-gray-800">
                    {{ __('Perfil de Administrador') }}
                </h2>
            </div>
        @endif

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border-t-4 border-yellow-600">
                    <div class="max-w-xl">
                        <livewire:profile.update-profile-information-form />
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border-t-4 border-gray-800">
                    <div class="max-w-xl">
                        <header class="mb-4">
                            <h2 class="text-lg font-medium text-gray-900 font-serif">
                                {{ __('Seguridad y Contraseña') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                Asegúrese de que su cuenta esté usando una contraseña larga y aleatoria para mantenerse segura.
                            </p>
                        </header>
                        <livewire:profile.update-password-form />
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg border border-red-100">
                    <div class="max-w-xl">
                        <livewire:profile.delete-user-form />
                    </div>
                </div>
            </div>
        </div>

    @endcomponent