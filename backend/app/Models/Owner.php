<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'phone',
        'email',
    ];

    /**
     * Un propriétaire peut avoir PLUSIEURS parkings
     */
    public function parkings()
    {
        return $this->hasMany(Parking::class);
    }
}
