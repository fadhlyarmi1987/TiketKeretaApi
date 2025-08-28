<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'uuid','user_id','trip_id','pnr','status',
        'total_amount','total_tax','total_fee','expires_at'
    ];

    protected static function booted() {
        static::creating(function($booking) {
            $booking->uuid = (string) Str::uuid();
            $booking->pnr = strtoupper(Str::random(8));
        });
    }

    public function trip() {
        return $this->belongsTo(Trip::class);
    }

    public function passengers() {
        return $this->hasMany(Passenger::class);
    }

    public function tickets() {
        return $this->hasMany(Ticket::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }
}
