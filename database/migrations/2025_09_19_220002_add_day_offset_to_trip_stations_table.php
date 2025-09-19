<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('trip_stations', function (Blueprint $table) {
        $table->integer('day_offset')->default(0); // 0 = hari H, 1 = H+1, dst
    });
}

public function down()
{
    Schema::table('trip_stations', function (Blueprint $table) {
        $table->dropColumn('day_offset');
    });
}

};
