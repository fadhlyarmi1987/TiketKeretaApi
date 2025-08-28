<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('train_id')->constrained()->cascadeOnDelete();
            $table->foreignId('origin_station_id')->constrained('stations')->cascadeOnDelete();
            $table->foreignId('destination_station_id')->constrained('stations')->cascadeOnDelete();

            $table->date('travel_date')->index();
            $table->time('departure_time');
            $table->time('arrival_time');

            $table->enum('status', ['SCHEDULED','DELAYED','CANCELLED','COMPLETED'])->default('SCHEDULED');
            $table->timestamps();

            $table->index(['train_id','travel_date']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('trips');
    }
};
