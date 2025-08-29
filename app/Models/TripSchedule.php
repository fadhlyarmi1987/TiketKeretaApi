<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TripSchedule extends Model
{
    use HasFactory;

    protected $table = 'trip_schedules';

    // Kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'trip_id',
        'travel_date',
        'departure_time',
        'arrival_time',
        'status',
    ];

    protected $casts = [
        'travel_date'   => 'date',
        'departure_time'=> 'datetime:H:i',
        'arrival_time'  => 'datetime:H:i',
    ];

    // Relasi: TripSchedule milik 1 Trip
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
