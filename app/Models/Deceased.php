<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;
class Deceased extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'birth_date',
        'death_date',
        'location',
        'biography',
        'photo_path',
        'is_public',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
        'is_public' => 'boolean',
    ];

    public function photos()
    {
        return $this->hasMany(DeceasedPhoto::class);
    }
}