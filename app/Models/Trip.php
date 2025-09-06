<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'train_id',
        'origin_station_id',
        'destination_station_id',
        'departure_time',
        'arrival_time',
        'status',
        'train_name',
    ];

    // Relasi ke Train
    public function train()
    {
        return $this->belongsTo(Train::class);
    }

    // Relasi ke Station (asal)
    public function origin()
    {
        return $this->belongsTo(Station::class, 'origin_station_id');
    }

    // Relasi ke Station (tujuan)
    public function destination()
    {
        return $this->belongsTo(Station::class, 'destination_station_id');
    }

    // Relasi ke trip_stations (stasiun yang dilewati)
    public function tripStations()
    {
        return $this->hasMany(TripStation::class);
    }

    public function stations()
    {
        return $this->hasMany(TripStation::class);
    }

    public function availableSeats($date = null)
{
    $date = $date ?? now()->toDateString();

    return Seat::whereHas('carriage.train.trips', function ($q) {
            $q->where('id', $this->id);
        })
        ->whereDoesntHave('passengers', function ($q) use ($date) {
            $q->where('departure_date', $date);
        });
}
}
