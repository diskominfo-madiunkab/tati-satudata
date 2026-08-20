<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data', function (Blueprint $table) {
            if (!Schema::hasColumn('data', 'sumber_referensi')) {
                $table->string('sumber_referensi')->nullable()->after('sumber_data');
            }
            if (!Schema::hasColumn('data', 'level_data')) {
                $table->string('level_data')->nullable()->default('Kabupaten'); // Kabupaten, Kecamatan, Desa/Kelurahan
            }
            if (!Schema::hasColumn('data', 'periode_data')) {
                $table->string('periode_data')->nullable()->default('Tahunan'); // Tahunan, Bulanan, Triwulan, Semesteran
            }
            if (!Schema::hasColumn('data', 'kode_referensi_wilayah')) {
                $table->string('kode_referensi_wilayah')->nullable();
            }
            if (!Schema::hasColumn('data', 'kode_referensi_bps')) {
                $table->string('kode_referensi_bps')->nullable();
            }
            if (!Schema::hasColumn('data', 'data_grid_json')) {
                $table->longText('data_grid_json')->nullable();
            }
        });

        Schema::table('standar_data', function (Blueprint $table) {
            if (!Schema::hasColumn('standar_data', 'kode_referensi_bappenas')) {
                $table->string('kode_referensi_bappenas')->nullable();
            }
            if (!Schema::hasColumn('standar_data', 'kode_referensi_bps')) {
                $table->string('kode_referensi_bps')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('data', function (Blueprint $table) {
            $table->dropColumn([
                'sumber_referensi',
                'level_data',
                'periode_data',
                'kode_referensi_wilayah',
                'kode_referensi_bps',
                'data_grid_json'
            ]);
        });

        Schema::table('standar_data', function (Blueprint $table) {
            $table->dropColumn([
                'kode_referensi_bappenas',
                'kode_referensi_bps'
            ]);
        });
    }
};
