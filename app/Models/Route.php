<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Route extends Model
{
    protected $fillable = [
        'train_id', 'origin_station_id', 'destination_station_id',
        'departure_time', 'arrival_time', 'price'
    ];

    public function train()
    {
        return $this->belongsTo(Train::class);
    }

    public function originStation()
    {
        return $this->belongsTo(Station::class, 'origin_station_id');
    }

    public function destinationStation()
    {
        return $this->belongsTo(Station::class, 'destination_station_id');
    }
}
