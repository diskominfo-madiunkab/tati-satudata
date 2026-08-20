<?php

namespace Database\Seeders;

use App\Models\Regulasi;
use App\Models\JadwalTerbit;
use Illuminate\Database\Seeder;

class SdiFeatureSeeder extends Seeder
{
    public function run(): void
    {
        // Regulasi
        if (Regulasi::count() == 0) {
            Regulasi::create([
                'judul' => 'Peraturan Presiden Nomor 39 Tahun 2019',
                'nomor' => '39 Tahun 2019',
                'tahun' => 2019,
                'tentang' => 'Satu Data Indonesia',
                'kategori' => 'Peraturan Presiden',
                'status' => 'Berlaku',
            ]);

            Regulasi::create([
                'judul' => 'Peraturan Bupati Madiun Nomor 54 Tahun 2021',
                'nomor' => '54 Tahun 2021',
                'tahun' => 2021,
                'tentang' => 'Penyelenggaraan Satu Data Kabupaten Madiun',
                'kategori' => 'Peraturan Bupati',
                'status' => 'Berlaku',
            ]);

            Regulasi::create([
                'judul' => 'Keputusan Bupati Madiun tentang Forum Satu Data Kabupaten Madiun',
                'nomor' => '188.45/215/KPTS/402.013/2022',
                'tahun' => 2022,
                'tentang' => 'Pembentukan Forum Satu Data Indonesia Tingkat Kabupaten Madiun',
                'kategori' => 'Keputusan Bupati',
                'status' => 'Berlaku',
            ]);

            Regulasi::create([
                'judul' => 'Petunjuk Teknis Pengelolaan Metadata dan Standar Data Statistik Sektoral',
                'nomor' => '01/JUKNIS/SDI/2023',
                'tahun' => 2023,
                'tentang' => 'Petunjuk Teknis Tata Kelola Standar Data dan Metadata Statistik Sektoral Kabupaten Madiun',
                'kategori' => 'Petunjuk Teknis',
                'status' => 'Berlaku',
            ]);
        }

        // Jadwal Rencana Terbit Buku Publikasi
        if (JadwalTerbit::count() == 0) {
            JadwalTerbit::create([
                'judul_buku' => 'Kabupaten Madiun Dalam Angka 2026',
                'penyusun' => 'Badan Pusat Statistik Kab. Madiun & Diskominfo',
                'tahun' => 2026,
                'rencana_terbit' => '2026-02-28',
                'frekuensi_terbit' => 'Tahunan',
                'status_terbit' => 'Terbit',
                'deskripsi' => 'Publikasi komprehensif memuat data statistik sektoral dan indikator makro pembangunan Kabupaten Madiun tahun 2026.',
            ]);

            JadwalTerbit::create([
                'judul_buku' => 'Statistik Sektoral Daerah Kabupaten Madiun 2026',
                'penyusun' => 'Dinas Komunikasi dan Informatika Kab. Madiun',
                'tahun' => 2026,
                'rencana_terbit' => '2026-08-30',
                'frekuensi_terbit' => 'Tahunan',
                'status_terbit' => 'Proses Penyusunan',
                'deskripsi' => 'Kompilasi data statistik sektoral dari seluruh Produsen Data (OPD) di lingkungan Pemerintah Kabupaten Madiun.',
            ]);

            JadwalTerbit::create([
                'judul_buku' => 'Profil Kesehatan Kabupaten Madiun 2026',
                'penyusun' => 'Dinas Kesehatan Kab. Madiun',
                'tahun' => 2026,
                'rencana_terbit' => '2026-10-15',
                'frekuensi_terbit' => 'Tahunan',
                'status_terbit' => 'Direncanakan',
                'deskripsi' => 'Data capaian indikator kesehatan masyarakat, fasilitas kesehatan, dan tenaga medis di Kabupaten Madiun.',
            ]);

            JadwalTerbit::create([
                'judul_buku' => 'Indikator Kesejahteraan Rakyat Kabupaten Madiun 2026',
                'penyusun' => 'Bappeda & Diskominfo Kab. Madiun',
                'tahun' => 2026,
                'rencana_terbit' => '2026-11-20',
                'frekuensi_terbit' => 'Tahunan',
                'status_terbit' => 'Direncanakan',
                'deskripsi' => 'Analisis data indikator kesejahteraan sosial, kemiskinan, ketenagakerjaan, dan IPM Kabupaten Madiun.',
            ]);
        }
    }
}
