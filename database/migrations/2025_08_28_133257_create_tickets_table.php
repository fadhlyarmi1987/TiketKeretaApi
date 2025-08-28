<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('passenger_id')->constrained()->cascadeOnDelete();

            $table->foreignId('carriage_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('seat_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('class', ['ECONOMY','BUSINESS','EXECUTIVE']);
            $table->string('ticket_number', 30)->unique(); // nomor tiket/ETKT
            $table->unsignedInteger('price'); // final per penumpang (include tax/fee proratanya)
            $table->enum('status', ['RESERVED','ISSUED','CHECKED_IN','USED','REFUNDED','VOID'])->default('RESERVED');

            $table->timestamps();

            $table->index(['booking_id','status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('tickets');
    }
};
