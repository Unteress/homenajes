@php
    use App\Models\Deceased;
    use App\Models\User;
    use Illuminate\Support\Facades\Request;
    use Carbon\Carbon;

    // 1. Capturar fecha del filtro
    $fecha = Request::input('date');

    // 2. Preparar consultas
    $deceasedQuery = Deceased::query();
    $usersQuery = User::where('role', '!=', 'admin');

    // 3. Aplicar filtro si existe
    if ($fecha) {
        $deceasedQuery->whereDate('created_at', $fecha);
        $usersQuery->whereDate('created_at', $fecha);
    }

    // 4. Obtener totales finales
    $totalDeceased = $deceasedQuery->count();
    $totalUsers = $usersQuery->count();
@endphp

<x-app-layout>
    <x-slot name="header">
        {{ __('Resumen General') }}
    </x-slot>
    
    {{-- BLOQUE DE BIENVENIDA --}}
    <div class="bg-white rounded-lg shadow-lg border-t-4 border-yellow-600 p-8 mb-8">
        <div class="flex flex-col md:flex-row items-center md:items-start">
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-4xl font-serif font-bold text-gray-900 mb-4">
                    Bienvenido, {{ Auth::user()->name }}
                </h1>
                <p class="text-gray-600 max-w-2xl text-lg leading-relaxed">
                    Utilice el menú lateral izquierdo para navegar por las diferentes secciones de administración. 
                    Desde este panel central podrá gestionar de manera integral el registro de fallecidos, moderar los homenajes y mantener vivo el legado de los seres queridos.
                </p>
            </div>
        </div>
    </div>

    {{-- AQUI ESTÁ EL FILTRO (EN EL MEDIO) --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        
        {{-- Indicador visual de qué se está mostrando --}}
        <div>
            @if($fecha)
                <h3 class="font-serif font-bold text-xl text-gray-800 border-l-4 border-yellow-600 pl-3">
                    Resultados del día: <span class="text-yellow-600">{{ Carbon::parse($fecha)->format('d/m/Y') }}</span>
                </h3>
            @else
                <h3 class="font-serif font-bold text-xl text-gray-800 border-l-4 border-gray-800 pl-3">
                    Estadísticas Totales (Histórico)
                </h3>
            @endif
        </div>

        {{-- Formulario selector de fecha --}}
        <form action="{{ route('dashboard') }}" method="GET" class="flex items-center bg-white p-2 rounded-lg shadow-sm border border-gray-200">
            <label for="date" class="text-sm font-bold text-gray-600 mr-3 uppercase tracking-wider">Filtrar por fecha:</label>
            <input 
                type="date" 
                name="date" 
                id="date"
                value="{{ $fecha }}" 
                onchange="this.form.submit()"
                class="border-gray-300 focus:border-yellow-600 focus:ring-yellow-600 rounded-md text-sm shadow-sm"
            >
            @if($fecha)
                <a href="{{ route('dashboard') }}" class="ml-3 text-red-500 hover:text-red-700 font-bold text-sm underline decoration-2 underline-offset-4" title="Ver todo">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    {{-- CUADRITOS DE ESTADÍSTICAS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Tarjeta 1: Fallecidos --}}
        <div class="bg-white p-6 rounded-lg shadow-md border-l-8 border-gray-800 hover:shadow-xl transition-shadow">
            <div class="text-gray-500 text-xs uppercase font-black tracking-widest">
                {{ $fecha ? 'Fallecidos (Fecha)' : 'Total Fallecidos' }}
            </div>
            <div class="text-4xl font-bold text-gray-900 mt-2 font-serif">
                {{ number_format($totalDeceased) }}
            </div>
        </div>
        
        {{-- Tarjeta 2: Homenajes (Placeholder) --}}
        <div class="bg-white p-6 rounded-lg shadow-md border-l-8 border-yellow-500 hover:shadow-xl transition-shadow">
            <div class="text-gray-500 text-xs uppercase font-black tracking-widest">Homenajes Hoy</div>
            <div class="text-4xl font-bold text-gray-900 mt-2 font-serif">0</div>
        </div>
        
        {{-- Tarjeta 3: Usuarios --}}
        <div class="bg-white p-6 rounded-lg shadow-md border-l-8 border-gray-800 hover:shadow-xl transition-shadow">
            <div class="text-gray-500 text-xs uppercase font-black tracking-widest">
                {{ $fecha ? 'Usuarios (Fecha)' : 'Usuarios Registrados' }}
            </div>
            <div class="text-4xl font-bold text-gray-900 mt-2 font-serif">
                {{ number_format($totalUsers) }}
            </div>
        </div>
    </div>
</x-app-layout>