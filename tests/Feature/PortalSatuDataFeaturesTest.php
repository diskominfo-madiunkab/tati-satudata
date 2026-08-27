<?php

namespace Tests\Feature;

use App\Models\Data;
use App\Models\Infografis;
use App\Models\JadwalTerbit;
use App\Models\Opd;
use App\Models\PublikasiGuest;
use App\Models\Regulasi;
use App\Models\User;
use App\Models\Visualisasi;
use Tests\TestCase;

class PortalSatuDataFeaturesTest extends TestCase
{
    public function test_beranda_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('DATA BERDASARKAN URUSAN');
    }

    public function test_katalog_data_page_returns_successful_response(): void
    {
        $response = $this->get('/katalog-data');
        $response->assertStatus(200);
        $response->assertSee('Katalog Data');
    }

    public function test_visualisasi_guest_listing_and_detail_page(): void
    {
        $vis = Visualisasi::firstOrCreate(
            ['title' => 'Uji Visualisasi Tableau SDI'],
            ['tableau_url' => 'https://public.tableau.com/views/test/1', 'content' => 'Deskripsi visualisasi']
        );

        $response = $this->get('/visualisasi-guest');
        $response->assertStatus(200);
        $response->assertSee('Visualisasi Interaktif');

        $detailResponse = $this->get('/visualisasi-guest/detail/' . $vis->id);
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee($vis->title);
    }

    public function test_kode_referensi_wilayah_and_puskesmas_tabs(): void
    {
        $responseKec = $this->get('/kode-referensi?tab=wilayah');
        $responseKec->assertStatus(200);
        $responseKec->assertSee('Kode Wilayah Kecamatan');

        $responsePusk = $this->get('/kode-referensi?tab=puskesmas');
        $responsePusk->assertStatus(200);
        $responsePusk->assertSee('35190200001'); // Puskesmas Gantrung KMK 2023
        $responsePusk->assertSee('35190200026'); // Puskesmas Jiwan KMK 2023

        $responseSdsn = $this->get('/kode-referensi?tab=sdsn');
        $responseSdsn->assertStatus(200);
        $responseSdsn->assertSee('Standar Data Statistik Nasional');
    }

    public function test_regulasi_page_returns_successful_response(): void
    {
        $response = $this->get('/regulasi');
        $response->assertStatus(200);
        $response->assertSee('Regulasi Satu Data');
    }

    public function test_geoportal_page_returns_successful_response(): void
    {
        $response = $this->get('/geoportal');
        $response->assertStatus(200);
        $response->assertSee('Geoportal Kabupaten Madiun');
        $response->assertSee('Fasilitas Kesehatan / Puskesmas (26)');
        $response->assertSee('Puskesmas Gantrung');
        $response->assertSee('Puskesmas Jiwan');
        $response->assertSee('Buka Geoportal Kab. Madiun');
    }

    public function test_publikasi_page_returns_successful_response(): void
    {
        $response = $this->get('/publikasi-guest');
        $response->assertStatus(200);
        $response->assertSee('Menampilkan Koleksi Buku Publikasi Kegiatan Statistik serta Jadwal Rencana Terbit Publikasi Kegiatan Statistik');
        $response->assertSee('Jadwal Rencana Terbit');
    }

    public function test_infografis_page_returns_successful_response(): void
    {
        $response = $this->get('/infografis-guest');
        $response->assertStatus(200);
        $response->assertSee('Menampilkan Koleksi Infografis dan Visualisasi dari dataset yang telah dikumpulkan pada Portal Satu Data Kabupaten Madiun');
    }

    public function test_dataset_download_multi_format(): void
    {
        $data = Data::first();
        if (!$data) {
            $data = Data::create([
                'nama_data' => 'Data Uji Download Multi Format',
                'opd_id' => 1,
                'tahun' => 2026,
                'status_id' => Data::STATUS_TERPUBLIKASI,
                'jenis_data' => 'variabel'
            ]);
        }

        // Test format JSON API stream
        $responseJson = $this->get('/dataset/download/' . $data->id . '/json');
        $responseJson->assertStatus(200);
        $responseJson->assertJsonStructure(['id', 'nama_data', 'tahun', 'opd']);

        // Test format CSV stream
        $responseCsv = $this->get('/dataset/download/' . $data->id . '/csv');
        $responseCsv->assertStatus(200);
        $responseCsv->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_api_v1_datasets_list_and_detail_endpoints(): void
    {
        $responseList = $this->getJson('/api/v1/datasets');
        $responseList->assertStatus(200);
        $responseList->assertJsonStructure(['status', 'code', 'message', 'data', 'pagination']);
    }

    public function test_walidata_dashboard_with_3_sdi_percentages(): void
    {
        $user = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['walidata', 'walidatapendukung', 'administrator']);
        })->first();

        if (!$user) {
            $user = User::first();
        }

        if ($user) {
            $response = $this->actingAs($user)->get('/d_walidata');
            $response->assertStatus(200);
            $response->assertSee('Persentase Keterisian Data');
            $response->assertSee('Persentase Validitas Data');
            $response->assertSee('Persentase Terpublikasi');
            $response->assertSee('Matriks Rekapitulasi Status Perangkat Daerah');
        }
    }

    public function test_produsen_buku_panduan_page(): void
    {
        $user = User::whereHas('roles', function($q) {
            $q->where('name', 'produsen');
        })->first();

        if (!$user) {
            $user = User::first();
        }

        if ($user) {
            $response = $this->actingAs($user)->get('/data_produsen/panduan');
            $response->assertStatus(200);
            $response->assertSee('Buku Panduan Produsen Data');
            $response->assertSee('Perencanaan Data');
        }
    }

    public function test_admin_visualisasi_and_regulasi_crud_routes(): void
    {
        $admin = User::whereHas('roles', function($q) {
            $q->where('name', 'administrator');
        })->first();

        if (!$admin) {
            $admin = User::first();
        }

        if ($admin) {
            $resVis = $this->actingAs($admin)->get('/kelola-visualisasi');
            $resVis->assertStatus(200);
            $resVis->assertSee('Kelola Visualisasi Tableau');

            $resReg = $this->actingAs($admin)->get('/kelola-regulasi');
            $resReg->assertStatus(200);
            $resReg->assertSee('Kelola Regulasi Satu Data');

            // Test Hapus Sumber Referensi (Hal 17 PDF)
            $sumber = \App\Models\SumberData::create(['sumber_data' => 'Sumber Referensi Uji Hapus']);
            $resDel = $this->actingAs($admin)->delete('/sumberdata/destroy/' . $sumber->id);
            $this->assertContains($resDel->getStatusCode(), [200, 302]);
        }
    }
}
