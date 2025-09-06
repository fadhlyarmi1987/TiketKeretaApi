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


    //relasi ke Bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class, 'seat_id');
    }
}
