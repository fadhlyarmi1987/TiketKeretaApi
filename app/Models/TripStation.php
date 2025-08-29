<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripStation extends Model
{
    protected $fillable = [
        'trip_id',
        'station_id',
        'train_name',
        'station_name',
        'arrival_time',
        'departure_time',
        'station_order',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    // ✅ event: saat create atau update, isi otomatis nama kereta & stasiun
    protected static function booted()
    {
        static::creating(function ($tripStation) {
            if ($tripStation->trip && $tripStation->trip->train) {
                $tripStation->train_name = $tripStation->trip->train->name;
            }

            if ($tripStation->station) {
                $tripStation->station_name = $tripStation->station->name;
            }
        });

        static::updating(function ($tripStation) {
            if ($tripStation->trip && $tripStation->trip->train) {
                $tripStation->train_name = $tripStation->trip->train->name;
            }

            if ($tripStation->station) {
                $tripStation->station_name = $tripStation->station->name;
            }
        });
    }
}
