<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Train;
use Illuminate\Http\Request;


class TrainController extends Controller
{
    public function index()
    {
        // Tampilkan train beserta relasi carriages dan seats
        return Train::with(['carriages.seats'])->get();
    }

    public function show($id)
    {
        $kereta = Train::with('carriages.seats')->findOrFail($id);
        // return view('admin.kereta.show', compact('kereta'));
        return Train::with(['carriages.seats'])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $train = Train::create($request->all());

        // return juga relasinya
        return $train->load(['carriages.seats']);
    }

    public function update(Request $request, $id)
    {
        $train = Train::findOrFail($id);
        $train->update($request->all());

        // return juga relasinya
        return $train->load(['carriages.seats']);
    }



    public function destroy($id)
    {
        $train = Train::findOrFail($id);
        $train->delete();
        return response()->json(['message' => 'deleted']);
    }
}
