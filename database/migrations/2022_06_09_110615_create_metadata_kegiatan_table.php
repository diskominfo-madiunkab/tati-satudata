<?php

use App\Models\Data;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('metadata_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Data::class, 'data_id')->constrained('data')->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('judul_kegiatan')->nullable();
            $table->integer('tahun_kegiatan')->nullable();
            $table->string('kode_kegiatan')->nullable();
            $table->tinyInteger('pengumpulan_data')->nullable();
            $table->tinyInteger('sektor_kegiatan')->nullable();
            $table->tinyInteger('jenis_kegiatan')->nullable();
            $table->tinyInteger('level_estimasi')->nullable();
            $table->string('instansi_penyelenggara')->nullable();
            $table->string('alamat_penyelenggara')->nullable();
            $table->string('telepon_penyelenggara')->nullable();
            $table->string('faksimile_penyelenggara')->nullable();
            $table->string('eselon_1')->nullable();
            $table->string('eselon_2')->nullable();
            $table->string('alamat_penanggungjawab')->nullable();
            $table->string('telepon_penanggungjawab')->nullable();
            $table->string('email_penanggungjawab')->nullable();
            $table->string('faksimile_penanggungjawab')->nullable();
            $table->longText('latar_belakang')->nullable();
            $table->longText('tujuan_kegiatan')->nullable();
            $table->date('jadwal_perencanaan_kegiatan')->nullable();
            $table->date('jadwal_perencanaan_desain')->nullable();
            $table->date('jadwal_pengumpulan_data')->nullable();
            $table->date('jadwal_pengolahan_data')->nullable();
            $table->date('jadwal_analasis_data')->nullable();
            $table->date('jadwal_diseminasi_hasil')->nullable();
            $table->date('jadwal_evaluasi')->nullable();
            $table->json('variabel_dikumpulkan')->nullable();
            $table->tinyInteger('kegiatan_dilakukan')->nullable();
            $table->tinyInteger('frekuensi_penyelenggaraan')->nullable();
            $table->tinyInteger('tipe_pengumpulan_data')->nullable();
            $table->tinyInteger('cakupan_wilayah_pengumpulan_data')->nullable();
            $table->string('provinsi_kegiatan')->nullable();
            $table->string('kota_kegiatan')->nullable();
            $table->string('metode_pengumpulan_data')->nullable();
            $table->string('sarana_pengumpulan_data')->nullable();
            $table->string('unit_pengumpulan_data')->nullable();
            $table->string('jenis_rancangan_sampel')->nullable();
            $table->string('metode_pemilihan_sampel_tahap_akhir')->nullable();
            $table->string('metode_sampel_yang_digunakan')->nullable();
            $table->string('kerangka_sampel_tahap_akhir')->nullable();
            $table->string('nilai_perkiraan_sampling_error_variabel_utama')->nullable();
            $table->longText('unit_sampel')->nullable();
            $table->longText('fraksi_sampel_keseluruhan')->nullable();
            $table->string('unit_observasi')->nullable();
            $table->string('pilot_survey')->nullable();
            $table->string('metode_pemeriksaan_kualitas_pengumpulan_data')->nullable();
            $table->string('penyesuaian_nonrespon')->nullable();
            $table->string('petugas_pengumpulan_data')->nullable();
            $table->string('pendidikan_petugas_pengumpulan_data')->nullable();
            $table->string('jumlah_petugas_supervisor')->nullable();
            $table->string('jumlah_petugas_pengumpul_data')->nullable();
            $table->string('pelatihan_petugas')->nullable();
            $table->string('editing')->nullable();
            $table->string('coding')->nullable();
            $table->string('data_entry')->nullable();
            $table->string('validasi')->nullable();
            $table->string('metode_analisis')->nullable();
            $table->string('unit_analisis')->nullable();
            $table->string('tingkat_penyajian_hasil_analisis')->nullable();
            $table->string('hardcopy')->nullable();
            $table->string('softcopy')->nullable();
            $table->string('data_mikro')->nullable();
            $table->json('rencana_publikasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metadata_kegiatan');
    }
};
