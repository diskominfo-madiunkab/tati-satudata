<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Table Visualisasis (Tableau visualisations)
        if (!Schema::hasTable('visualisasis')) {
            Schema::create('visualisasis', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('tableau_url')->nullable();
                $table->longText('content')->nullable();
                $table->timestamps();
            });
        }

        // 2. Add columns to data table for Table 5.2 SDI form requirements
        Schema::table('data', function (Blueprint $table) {
            if (!Schema::hasColumn('data', 'kode_ddd')) {
                $table->string('kode_ddd')->nullable()->after('id');
            }
            if (!Schema::hasColumn('data', 'kode_ddp')) {
                $table->string('kode_ddp')->nullable()->after('kode_ddd');
            }
            if (!Schema::hasColumn('data', 'klasifikasi_data')) {
                $table->string('klasifikasi_data')->nullable()->default('Non-Prioritas');
            }
            if (!Schema::hasColumn('data', 'jenis_data')) {
                $table->string('jenis_data')->nullable()->default('Variabel'); // Indikator / Variabel
            }
            if (!Schema::hasColumn('data', 'kode_standar_data')) {
                $table->string('kode_standar_data')->nullable();
            }
            if (!Schema::hasColumn('data', 'konsep')) {
                $table->text('konsep')->nullable();
            }
            if (!Schema::hasColumn('data', 'definisi')) {
                $table->text('definisi')->nullable();
            }
            if (!Schema::hasColumn('data', 'ukuran')) {
                $table->string('ukuran')->nullable();
            }
            if (!Schema::hasColumn('data', 'satuan')) {
                $table->string('satuan')->nullable();
            }
            if (!Schema::hasColumn('data', 'klasifikasi_penyajian')) {
                $table->string('klasifikasi_penyajian')->nullable();
            }
            if (!Schema::hasColumn('data', 'jadwal_pemutakhiran')) {
                $table->string('jadwal_pemutakhiran')->nullable();
            }
            if (!Schema::hasColumn('data', 'kategori_rad')) {
                $table->string('kategori_rad')->nullable();
            }
            if (!Schema::hasColumn('data', 'views_count')) {
                $table->unsignedBigInteger('views_count')->default(0);
            }
            if (!Schema::hasColumn('data', 'downloads_count')) {
                $table->unsignedBigInteger('downloads_count')->default(0);
            }
        });

        // 3. Add columns to publikasi_guests table
        Schema::table('publikasi_guests', function (Blueprint $table) {
            if (!Schema::hasColumn('publikasi_guests', 'opd_id')) {
                $table->unsignedBigInteger('opd_id')->nullable();
            }
            if (!Schema::hasColumn('publikasi_guests', 'instansi')) {
                $table->string('instansi')->nullable();
            }
            if (!Schema::hasColumn('publikasi_guests', 'tahun')) {
                $table->string('tahun', 10)->nullable();
            }
            if (!Schema::hasColumn('publikasi_guests', 'jadwal_rencana_terbit')) {
                $table->string('jadwal_rencana_terbit')->nullable();
            }
            if (!Schema::hasColumn('publikasi_guests', 'jadwal_terbit')) {
                $table->string('jadwal_terbit')->nullable();
            }
            if (!Schema::hasColumn('publikasi_guests', 'status')) {
                $table->string('status', 30)->default('Terbit'); // Terbit / Belum Terbit
            }
            if (!Schema::hasColumn('publikasi_guests', 'frekuensi')) {
                $table->string('frekuensi', 50)->nullable()->default('Tahunan');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visualisasis');
    }
};
