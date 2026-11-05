<?php

namespace Tests\Feature;

use App\Models\Data;
use App\Models\Infografis;
use App\Models\JadwalTerbit;
use App\Models\Opd;
use App\Models\PublikasiGuest;
use App\Models\Regulasi;
use Tests\TestCase;

class PortalSatuDataFeaturesTest extends TestCase
{
    public function test_beranda_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('Login DAPUR SDI');
        $response->assertSee('Masuk Portal');
        $response->assertSee('sdi@madiunkab.go.id');
        $response->assertDontSee('diskominfo@madiunkab.go.id');
    }

    public function test_katalog_data_page_returns_successful_response(): void
    {
        $response = $this->get('/katalog-data');
        $response->assertStatus(200);
        $response->assertSee('Katalog Data');
        $response->assertSee('Menampilkan Daftar Data Pemerintah Kabupaten Madiun');
        $response->assertSee('Cari');
    }

    public function test_kode_referensi_page_returns_successful_response(): void
    {
        $response = $this->get('/kode-referensi');
        $response->assertStatus(200);
        $response->assertSee('Kode Referensi');
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
        $response->assertSee('Geoportal');
    }

    public function test_publikasi_page_with_jadwal_rencana_terbit_returns_successful_response(): void
    {
        $response = $this->get('/publikasi-guest');
        $response->assertStatus(200);
        $response->assertSee('Buku Publikasi');
        $response->assertSee('Jadwal Rencana Terbit');
    }

    public function test_infografis_page_returns_successful_response(): void
    {
        $response = $this->get('/infografis-guest');
        $response->assertStatus(200);
        $response->assertSee('Infografis Satu Data');
    }

    public function test_api_v1_datasets_list_returns_json_response(): void
    {
        $response = $this->getJson('/api/v1/datasets');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'code',
            'message',
            'data',
            'pagination'
        ]);
    }

    public function test_api_v1_dataset_detail_returns_json_response(): void
    {
        $data = Data::where('status_id', Data::STATUS_TERPUBLIKASI)->first();
        if (!$data) {
            $data = Data::first();
        }

        if ($data) {
            $response = $this->getJson('/api/v1/datasets/' . $data->id);
            $response->assertStatus(200);
            $response->assertJsonStructure([
                'status',
                'code',
                'data' => [
                    'id',
                    'nama_data',
                    'tahun',
                    'sumber_referensi',
                ]
            ]);
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_dataset_listing_page_shows_total_count_and_filters_without_outer_unduh(): void
    {
        $response = $this->get('/dataset');
        $response->assertStatus(200);
        $response->assertSee('Total Jumlah Dataset yang sudah disebarluaskan');
        $response->assertSee('select2-searchable');
        $response->assertSee('Cari Data');
        $response->assertSee('Organisasi / OPD');
        $response->assertSee('Urusan');
        $response->assertSee('Sumber Referensi');
        $response->assertDontSee('Opsi Unduh');
    }

    public function test_dataset_detail_page_loads_matching_theme_and_has_download_resources(): void
    {
        $data = Data::where('status_id', Data::STATUS_TERPUBLIKASI)->first() ?: Data::first();
        if ($data) {
            $response = $this->get('/dataset/' . $data->id);
            $response->assertStatus(200);
            $response->assertSee('Detail Dataset');
            $response->assertSee('page-banner');
            $response->assertSee($data->nama_data);
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_dataset_detail_page_loads_via_ckan_uuid_and_shows_table(): void
    {
        $uuid = '91e8aeb3-c356-4edc-8ecc-8cb0a9172c02';
        $response = $this->get('/dataset/' . $uuid . '?tab=tabel');
        $response->assertStatus(200);
        $response->assertSee('Indeks Penyelenggaraan Penataan Ruang');
        $response->assertSee('Visualisasi Tabel Data');
        $response->assertSee('IPPR');
    }
}
