<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carriage_id')->constrained('carriages')->onDelete('cascade');
            $table->string('seat_number');
            $table->string('position')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('seats');
    }
};
