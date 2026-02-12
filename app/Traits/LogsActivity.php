<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Boot del Trait. Laravel detecta automáticamente este método
     * si se llama boot[NombreDelTrait].
     */
    protected static function bootLogsActivity()
    {
        // Evento al CREAR un registro
        static::created(function ($model) {
            self::recordLog($model, 'created', null);
        });

        // Evento al ACTUALIZAR un registro
        static::updated(function ($model) {
            // Obtenemos solo lo que cambió
            $changes = $model->getChanges();
            // Ignoramos updated_at si es el único cambio
            if (count($changes) > 0) {
                self::recordLog($model, 'updated', $changes);
            }
        });

        // Evento al ELIMINAR un registro
        static::deleted(function ($model) {
            self::recordLog($model, 'deleted', $model->toArray()); // Guardamos copia de lo borrado
        });
    }

    /**
     * Función auxiliar para guardar en la BD
     */
    protected static function recordLog($model, $action, $details = null)
    {
        // Solo registramos si hay un usuario logueado (evitamos logs de seeders o tareas cron anónimas)
        if (Auth::check()) {
            ActivityLog::create([
                'user_id'    => Auth::id(),
                'action'     => $action,
                'model_type' => get_class($model),
                'model_id'   => $model->id,
                'details'    => $details, // Guardamos qué cambió
                'ip_address' => Request::ip(),
            ]);
        }
    }
}