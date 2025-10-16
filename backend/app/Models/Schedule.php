<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_type',
    ];

    /**
     * Un horaire peut être utilisé par PLUSIEURS parkings
     */
    public function parkings()
    {
        return $this->hasMany(Parking::class);
    }
}
