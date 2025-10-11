<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Spot extends Model
{
    use HasFactory;

    protected $fillable = [
        'parking_id',
        'floor_id',
        'occupied',
        'spot_number',
    ];

    protected $casts = [
        'occupied' => 'boolean',
        'spot_number' => 'integer',
    ];

    /**
     * Une place APPARTIENT À un parking
     */
    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }

    /**
     * Une place APPARTIENT À un étage
     */
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    /**
     * Marquer la place comme occupée
     */
    public function markAsOccupied()
    {
        $this->update(['occupied' => true]);
    }

    /**
     * Marquer la place comme disponible
     */
    public function markAsAvailable()
    {
        $this->update(['occupied' => false]);
    }

    /**
     * Vérifier si la place est disponible
     */
    public function isAvailable(): bool
    {
        return !$this->occupied;
    }

    /**
     * Scope pour filtrer les places disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('occupied', false);
    }

    /**
     * Scope pour filtrer les places occupées
     */
    public function scopeOccupied($query)
    {
        return $query->where('occupied', true);
    }
}
