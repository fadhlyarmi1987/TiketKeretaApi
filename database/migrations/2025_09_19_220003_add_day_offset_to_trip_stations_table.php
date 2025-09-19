<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('trip_stations', 'day_offset')) {
            Schema::table('trip_stations', function (Blueprint $table) {
                // gunakan unsignedTinyInteger, default 0, setelah departure_time (ubah jika perlu)
                $table->unsignedTinyInteger('day_offset')->default(0)->after('departure_time');
            });
        } else {
            // Jika kolom sudah ada tetapi nullable atau tanpa default, coba ubah.
            // NOTE: untuk ->change() butuh doctrine/dbal. Jika belum terpasang, jalankan:
            // composer require doctrine/dbal
            try {
                Schema::table('trip_stations', function (Blueprint $table) {
                    $table->unsignedTinyInteger('day_offset')->default(0)->nullable(false)->change();
                });
            } catch (\Throwable $e) {
                // jika change() gagal (tidak ada dbal) — lakukan fallback: set default via raw SQL
                DB::statement("ALTER TABLE `trip_stations` ALTER `day_offset` SET DEFAULT 0");
                DB::statement("UPDATE `trip_stations` SET `day_offset` = 0 WHERE `day_offset` IS NULL");
            }
        }
    }

    public function down()
    {
        if (Schema::hasColumn('trip_stations', 'day_offset')) {
            Schema::table('trip_stations', function (Blueprint $table) {
                $table->dropColumn('day_offset');
            });
        }
    }
};
