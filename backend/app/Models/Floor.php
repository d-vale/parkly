<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'parking_id',
    ];

    protected $casts = [
        'number' => 'integer',
    ];

    /**
     * Un étage APPARTIENT À un parking
     */
    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }

    /**
     * Un étage A PLUSIEURS places
     */
    public function spots()
    {
        return $this->hasMany(Spot::class);
    }

    /**
     * Récupérer les places disponibles sur cet étage
     */
    public function availableSpots()
    {
        return $this->spots()->where('occupied', false);
    }

    /**
     * Récupérer le nombre de places disponibles sur cet étage
     */
    public function getAvailableSpotsCountAttribute()
    {
        return $this->spots()->where('occupied', false)->count();
    }
}
