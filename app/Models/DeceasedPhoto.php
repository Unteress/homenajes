<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity; // <--- IMPORTAR

class DeceasedPhoto extends Model {
    
    use LogsActivity; // <--- USAR EL TRAIT

    protected $fillable = ['deceased_id', 'path', 'type'];

    public function deceased() {
        return $this->belongsTo(Deceased::class);
    }
}