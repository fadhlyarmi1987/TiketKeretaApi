<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TripStation;

class FillTripStationNamesSeeder extends Seeder
{
    public function run()
    {
        $tripStations = TripStation::with(['trip.train', 'station'])->get();

        foreach ($tripStations as $ts) {
            $ts->train_name = $ts->trip->train->name ?? null;
            $ts->station_name = $ts->station->name ?? null;
            $ts->save();
        }
    }
}
