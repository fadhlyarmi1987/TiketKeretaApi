<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // user app, bisa guest
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();

            $table->string('pnr', 12)->unique(); // kode booking
            $table->enum('status', ['INIT','PENDING_PAYMENT','PAID','ISSUED','CANCELLED','EXPIRED'])->default('INIT');

            $table->unsignedInteger('total_amount')->default(0);
            $table->unsignedInteger('total_tax')->default(0);
            $table->unsignedInteger('total_fee')->default(0);

            $table->timestamp('expires_at')->nullable(); // hold payment expired time
            $table->timestamps();

            $table->index(['trip_id','status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('bookings');
    }
};
