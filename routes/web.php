<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

// -----------------------------------------------------------------------------
// 1. CARGA DE RUTAS DE AUTENTICACIÓN
// -----------------------------------------------------------------------------
require __DIR__.'/auth.php';


// -----------------------------------------------------------------------------
// 2. RUTAS DE MANTENIMIENTO (¡USA ESTO PRIMERO!)
// -----------------------------------------------------------------------------
// Como cambiaste rutas, necesitas visitar esto para que Laravel se entere.
Route::get('/limpiar', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    return "<h1>✅ CACHÉ BORRADA</h1><p>Ahora intenta ver la imagen de nuevo.</p>";
});


// -----------------------------------------------------------------------------
// 3. ZONA PÚBLICA
// -----------------------------------------------------------------------------
Route::view('/', 'welcome')->name('welcome');
Volt::route('/homenaje/{deceased}', 'pages.public.profile')->name('public.profile');


// -----------------------------------------------------------------------------
// 4. SISTEMA DE REGISTRO
// -----------------------------------------------------------------------------
Volt::route('register', 'pages.auth.register-user')->name('register');
Volt::route('register-admin-secret', 'pages.auth.register')->name('register.admin');


// -----------------------------------------------------------------------------
// 5. LOGOUT
// -----------------------------------------------------------------------------
Route::post('/logout', function () {
    auth()->guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// -----------------------------------------------------------------------------
// 6. ZONA PROTEGIDAS
// -----------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Volt::route('/usuarios', 'pages.users.index')->name('users.index');
    Volt::route('/fallecidos', 'pages.deceased.index')->name('deceased.index');
    Volt::route('/fallecidos/crear', 'pages.deceased.form')->name('deceased.create');
    Volt::route('/fallecidos/{deceased}/editar', 'pages.deceased.form')->name('deceased.edit');
    Volt::route('/fallecidos/{deceased}/gallery', 'pages.deceased.gallery')->name('deceased.gallery');
    Volt::route('settings', 'pages.settings.index')->name('settings.index');
});


// -----------------------------------------------------------------------------
// 7. 🚑 MOTOR DE IMÁGENES (SIN ACCESO DIRECTO)
// -----------------------------------------------------------------------------
// Al haber borrado la carpeta 'storage' en public, esta ruta toma el control.
Route::get('/storage/{path}', function ($path) {
    
    // 1. Construir la ruta al archivo real
    $filePath = storage_path('app/public/' . $path);

    // 2. Verificar existencia
    if (!File::exists($filePath)) {
        abort(404);
    }

    // 3. Servir el archivo
    $file = File::get($filePath);
    $type = File::mimeType($filePath);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;

})->where('path', '.*'); // Importante: Permite subcarpetas