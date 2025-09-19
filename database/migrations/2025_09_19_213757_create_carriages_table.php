<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('carriages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('train_id')->constrained('trains')->onDelete('cascade');
            $table->string('code');
            $table->string('class');
            $table->integer('seat_count');
            $table->integer('order')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('carriages');
    }
};
