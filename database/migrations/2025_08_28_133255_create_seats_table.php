<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carriage_id')->constrained()->cascadeOnDelete();
            $table->string('seat_number', 10); // ex: 1A, 3B
            $table->enum('position', ['WINDOW','AISLE','MIDDLE'])->nullable();
            $table->timestamps();

            $table->unique(['carriage_id','seat_number']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('seats');
    }
};
