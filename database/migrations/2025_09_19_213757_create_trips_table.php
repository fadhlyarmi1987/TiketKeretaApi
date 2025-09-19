<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('train_id')->constrained('trains')->onDelete('cascade');
            $table->foreignId('origin_station_id')->constrained('stations');
            $table->foreignId('destination_station_id')->constrained('stations');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->string('status')->nullable();
            $table->string('train_name')->nullable();
            $table->integer('day_offset')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('trips');
    }
};
