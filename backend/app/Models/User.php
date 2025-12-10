<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Un utilisateur peut avoir PLUSIEURS parkings favoris
     */
    public function favorites()
    {
        return $this->belongsToMany(Parking::class, 'favorites')
            ->withTimestamps();
    }

    /**
     * Alias pour la relation favorites (legacy)
     * @deprecated Utilisez favorites() à la place
     */
    public function favoriteParkings()
    {
        return $this->favorites();
    }
}
