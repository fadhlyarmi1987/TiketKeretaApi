<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('carriages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('train_id')->constrained()->cascadeOnDelete();
            $table->string('code', 20);           // ex: K1-1
            $table->enum('class', ['ECONOMY','BUSINESS','EXECUTIVE']);
            $table->unsignedInteger('seat_count')->default(0);
            $table->unsignedInteger('order')->default(1); // urutan gerbong
            $table->timestamps();

            $table->unique(['train_id','code']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('carriages');
    }
};
