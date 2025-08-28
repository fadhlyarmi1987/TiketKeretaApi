<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Train extends Model
{
    protected $fillable = [
        'code',
        'name',
        'service_class',
        'carriage_count', // ini wajib sama persis dengan nama kolom di tabel
    ];

    public function carriages()
    {
        return $this->hasMany(Carriage::class);
    }
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
