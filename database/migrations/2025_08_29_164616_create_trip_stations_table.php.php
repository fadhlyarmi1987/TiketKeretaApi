<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // kalau tabel lama sudah ada, drop dulu
        Schema::dropIfExists('trip_stations');

        Schema::create('trip_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('station_id')->constrained()->cascadeOnDelete();

            // kolom tambahan langsung setelah station_id
            $table->string('train_name');
            $table->string('station_name');

            // waktu tiba & berangkat di stasiun tersebut
            $table->time('arrival_time')->nullable();
            $table->time('departure_time')->nullable();

            // urutan stasiun dalam trip (1 = asal, terakhir = tujuan)
            $table->unsignedInteger('order')->index();

            $table->timestamps();
            $table->unique(['trip_id', 'station_id']); // supaya 1 stasiun tidak dobel di trip yg sama
        });
    }

    public function down(): void {
        Schema::dropIfExists('trip_stations');
    }
};
