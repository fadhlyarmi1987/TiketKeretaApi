<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('trip_stations', function (Blueprint $table) {
            // tambahkan setelah station_id
            $table->string('train_name')->nullable()->after('station_id');
            $table->string('station_name')->nullable()->after('train_name');
        });
    }

    public function down(): void {
        Schema::table('trip_stations', function (Blueprint $table) {
            $table->dropColumn(['train_name', 'station_name']);
        });
    }
};
