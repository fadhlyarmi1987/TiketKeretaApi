<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('trip_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->foreignId('station_id')->constrained('stations');
            $table->string('train_name')->nullable();
            $table->string('station_name')->nullable();
            $table->time('arrival_time')->nullable();
            $table->time('departure_time')->nullable();
            $table->integer('station_order')->nullable();
            $table->integer('day_offset')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('trip_stations');
    }
};
