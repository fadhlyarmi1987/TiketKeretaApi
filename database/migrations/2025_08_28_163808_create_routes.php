<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('routes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('train_id')->constrained()->onDelete('cascade'); // kereta
    $table->foreignId('origin_station_id')->constrained('stations')->onDelete('cascade'); // stasiun asal
    $table->foreignId('destination_station_id')->constrained('stations')->onDelete('cascade'); // stasiun tujuan
    $table->dateTime('departure_time'); // jam berangkat
    $table->dateTime('arrival_time');   // jam tiba
    $table->integer('price'); // harga tiket (opsional)
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
