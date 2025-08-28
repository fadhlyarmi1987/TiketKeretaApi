<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'carriage_id',
        'seat_number',
        'position'
    ];

    // Relasi ke Carriage
    public function carriage()
    {
        return $this->belongsTo(Carriage::class);
    }
}
