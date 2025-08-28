<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carriage extends Model
{
    protected $fillable = [
        'train_id',
        'code',
        'class',
        'seat_count',
        'order'
    ];

    // Relasi ke Train
    public function train()
    {
        return $this->belongsTo(Train::class);
    }

    // Relasi ke Seat
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
