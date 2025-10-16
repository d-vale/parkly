<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Price extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'minutes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'minutes' => 'integer',
    ];

    /**
     * Un prix peut être utilisé par PLUSIEURS parkings
     */
    public function parkings()
    {
        return $this->hasMany(Parking::class);
    }

    /**
     * Arrondi automatique au 0.05 CHF (Franc Suisse)
     */
    public function setPriceAttribute($value)
    {
        // Arrondi au 0.05 CHF
        $this->attributes['price'] = round($value * 20) / 20;
    }

    /**
     * Méthode statique pour arrondir un montant au 0.05 CHF
     * Exemples: 9.99 → 10.00, 9.97 → 9.95, 2.51 → 2.50
     */
    public static function roundToSwissFranc(float $amount): float
    {
        return round($amount * 20) / 20;
    }

    /**
     * Vérifier si un prix est valide pour le Franc Suisse
     */
    public static function isValidSwissPrice(float $price): bool
    {
        $cents = round($price * 100);
        return $cents % 5 === 0;
    }
}
