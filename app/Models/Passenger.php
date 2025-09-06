<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $fillable = [
        'name',
        'nik',
        'jenis_kelamin',
        'tanggal_lahir',
        'booking_id',
        'seat_id',
    ];

    // Relasi ke Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Relasi ke Seat
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
