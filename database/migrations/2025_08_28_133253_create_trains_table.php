<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('trains', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();  // ex: KA-1
            $table->string('name', 100);           // ex: Argo Bromo Anggrek
            $table->enum('service_class', ['ECONOMY','BUSINESS','EXECUTIVE','MIX']);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('trains');
    }
};
