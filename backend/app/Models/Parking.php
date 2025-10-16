<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Parking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
        'schedule_id',
        'price_id',
        'city',
        'postal_code',
        'address',
        'type',
    ];

    protected $casts = [
        'postal_code' => 'integer',
    ];

    /**
     * Un parking APPARTIENT À un propriétaire
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    /**
     * Un parking APPARTIENT À un horaire
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * Un parking APPARTIENT À un prix
     */
    public function price()
    {
        return $this->belongsTo(Price::class);
    }

    /**
     * Un parking A PLUSIEURS étages
     */
    public function floors()
    {
        return $this->hasMany(Floor::class);
    }

    /**
     * Un parking A PLUSIEURS places
     */
    public function spots()
    {
        return $this->hasMany(Spot::class);
    }

    /**
     * Un parking PEUT ÊTRE favori de PLUSIEURS utilisateurs
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')
            ->withTimestamps();
    }

    /**
     * Récupérer les places disponibles (non occupées)
     */
    public function availableSpots()
    {
        return $this->spots()->where('occupied', false);
    }

    /**
     * Récupérer le nombre de places disponibles
     */
    public function getAvailableSpotsCountAttribute()
    {
        return $this->spots()->where('occupied', false)->count();
    }

    /**
     * Récupérer le nombre total de places
     */
    public function getTotalSpotsCountAttribute()
    {
        return $this->spots()->count();
    }
}
