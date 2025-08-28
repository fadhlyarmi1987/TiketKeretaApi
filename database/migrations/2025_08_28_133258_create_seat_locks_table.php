<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('seat_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('carriage_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seat_id')->constrained()->cascadeOnDelete();

            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->uuid('session_uuid')->nullable(); // kalau guest/belum booking

            $table->timestamp('locked_until')->index();
            $table->timestamps();

            $table->unique(['trip_id','seat_id']); // satu kursi hanya bisa terkunci satu pihak
        });
    }
    public function down(): void {
        Schema::dropIfExists('seat_locks');
    }
};
