<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('fares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->enum('class', ['ECONOMY','BUSINESS','EXECUTIVE']);
            $table->unsignedInteger('base_price');     // harga dasar
            $table->unsignedInteger('tax')->default(0);
            $table->unsignedInteger('fee')->default(0); // admin/service fee
            $table->timestamps();

            $table->unique(['trip_id','class']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('fares');
    }
};
