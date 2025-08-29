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
        Schema::create('trip_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();

            $table->date('travel_date')->index();
            $table->time('departure_time');  // jam berangkat dari origin
            $table->time('arrival_time');    // jam tiba di destination
            $table->enum('status', ['SCHEDULED', 'DELAYED', 'CANCELLED', 'COMPLETED'])->default('SCHEDULED');

            $table->timestamps();
            $table->unique(['trip_id', 'travel_date']); // 1 trip unik per hari
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
