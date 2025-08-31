<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['booking_id', 'seat_id', 'passenger_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }
}
