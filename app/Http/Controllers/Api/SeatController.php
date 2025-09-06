<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Train;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    /**
     * Ambil daftar carriage dan seats berdasarkan train_id
     */
    public function getSeatsByTrain($trainId)
    {
        $train = Train::with(['carriages.seats'])->find($trainId);

        if (!$train) {
            return response()->json([
                'success' => false,
                'message' => 'Train not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar seats berhasil diambil',
            'data' => $train->carriages->map(function ($carriage) {
                return [
                    'carriage_id' => $carriage->id,
                    'carriage_code' => $carriage->code,
                    'class' => $carriage->class,
                    'order' => $carriage->order,
                    'seats' => $carriage->seats->map(function ($seat) {
                        return [
                            'seat_id' => $seat->id,
                            'seat_number' => $seat->seat_number,
                            'position' => $seat->position,
                        ];
                    })
                ];
            })
        ]);
    }
}
