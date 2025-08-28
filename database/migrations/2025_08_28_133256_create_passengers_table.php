<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['ADULT','CHILD','INFANT'])->default('ADULT');
            $table->string('full_name', 120);
            $table->string('id_number', 50)->nullable(); // NIK/Passport
            $table->date('birth_date')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 120)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('passengers');
    }
};
