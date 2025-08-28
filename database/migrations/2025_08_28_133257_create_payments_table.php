<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->enum('channel', ['VA','CC','EWALLET','RETAIL','TRANSFER']);
            $table->string('provider', 50)->nullable(); // ex: Mandiri VA, BNI, Midtrans, Xendit
            $table->string('external_ref', 100)->nullable(); // order_id/ref gateway
            $table->unsignedInteger('amount');
            $table->enum('status', ['INIT','PENDING','PAID','FAILED','EXPIRED','REFUNDED'])->default('INIT');
            $table->json('payload')->nullable(); // response gateway
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['booking_id','status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('payments');
    }
};
