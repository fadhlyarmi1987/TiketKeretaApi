<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'pnr',
        'user_id',
        'trip_id',
        'seat_id',
        'departure_date',
        'status',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Trip
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // Relasi ke Seat
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function passengers()
{
    return $this->hasMany(Passenger::class);
}

}
