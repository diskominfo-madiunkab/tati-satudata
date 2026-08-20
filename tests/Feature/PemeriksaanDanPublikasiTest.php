<?php

namespace Tests\Feature;

use App\Models\Data;
use App\Models\Opd;
use App\Models\RevisiNote;
use App\Models\StandarData;
use App\Models\User;
use App\Models\VisualTable;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PemeriksaanDanPublikasiTest extends TestCase
{
    protected function getOrCreateUserWithRole(string $roleName, int $roleId = 2): User
    {
        $opd = Opd::first();
        $user = User::where('role_id', $roleId)->first();
        if (!$user) {
            $user = User::create([
                'name' => 'User ' . ucfirst($roleName),
                'email' => $roleName . '_' . time() . '@test.madiunkab.go.id',
                'password' => bcrypt('password123'),
                'role_id' => $roleId,
                'opd_id' => $opd ? $opd->id : 1,
            ]);
        }

        $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        if (!$user->hasRole($roleName)) {
            $user->assignRole($role);
        }

        return $user;
    }

    public function test_walidata_can_add_catatan_revisi_and_view_history(): void
    {
        $walidata = $this->getOrCreateUserWithRole('walidata', 2);
        $data = Data::create([
            'nama_data' => 'Data Uji Revisi ' . time(),
            'user_id' => $walidata->id,
            'opd_id' => $walidata->opd_id,
            'tahun' => 2026,
            'jenis_data' => 'Variabel',
            'sumber_data' => 'RPJMD',
            'status_id' => Data::STATUS_PROSES_VERIFIKASI,
        ]);

        $response = $this->actingAs($walidata)
            ->postJson('/data_walidata/verifikasi/' . $data->id . '/catatan-revisi', [
                'catatan' => 'Mohon definisi disesuaikan dengan Kamus Data SDSN BPS',
                'tahapan' => 'pemeriksaan'
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'ok' => true,
        ]);

        $data->refresh();
        $this->assertEquals(Data::STATUS_REVISI, $data->status_id);

        // Test get riwayat revisi
        $historyResponse = $this->actingAs($walidata)
            ->getJson('/data_walidata/verifikasi/' . $data->id . '/riwayat-revisi');

        $historyResponse->assertStatus(200);
        $historyResponse->assertJsonStructure([
            'ok',
            'data' => [
                '*' => ['id', 'catatan', 'tahapan', 'status_sebelumnya', 'status_sesudahnya', 'created_at']
            ]
        ]);
    }

    public function test_walidata_batch_verify_all_metadata_fields(): void
    {
        $walidata = $this->getOrCreateUserWithRole('walidata', 2);
        $data = Data::create([
            'nama_data' => 'Data Uji Batch Verify ' . time(),
            'user_id' => $walidata->id,
            'opd_id' => $walidata->opd_id,
            'tahun' => 2026,
            'jenis_data' => 'Variabel',
            'sumber_data' => 'RPJMD',
            'status_id' => Data::STATUS_PROSES_VERIFIKASI,
        ]);

        StandarData::create([
            'data_id' => $data->id,
            'konsep' => 'Kemiskinan',
            'definisi' => 'Ketidakmampuan memenuhi kebutuhan dasar',
            'klasifikasi' => 'Desil 1-4',
            'ukuran' => 'Jumlah',
            'satuan' => 'Jiwa',
        ]);

        $response = $this->actingAs($walidata)
            ->postJson('/data_walidata/verifikasi/' . $data->id . '/batch-verify');

        $response->assertStatus(200);
        $response->assertJson([
            'ok' => true,
            'message' => 'Seluruh metadata dan berkas berhasil diverifikasi (Sesuai)!'
        ]);
    }

    public function test_walidata_can_preview_dataset_before_publish(): void
    {
        $walidata = $this->getOrCreateUserWithRole('walidata', 2);
        $data = Data::create([
            'nama_data' => 'Data Uji Preview Publikasi ' . time(),
            'user_id' => $walidata->id,
            'opd_id' => $walidata->opd_id,
            'tahun' => 2026,
            'jenis_data' => 'Variabel',
            'sumber_data' => 'RPJMD',
            'status_id' => Data::STATUS_SIAP_PUBLIKASI,
        ]);

        $response = $this->actingAs($walidata)
            ->getJson('/data_walidata/publikasi/' . $data->id . '/preview');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'ok',
            'data' => [
                'id',
                'nama_data',
                'opd',
                'tahun',
                'sumber_referensi',
                'tables'
            ]
        ]);
    }
}
