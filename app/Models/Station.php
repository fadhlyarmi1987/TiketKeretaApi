<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = ['code','name','city','lat','lng'];

    public function originTrips() {
        return $this->hasMany(Trip::class, 'origin_station_id');
    }

    public function destinationTrips() {
        return $this->hasMany(Trip::class, 'destination_station_id');
    }
}
