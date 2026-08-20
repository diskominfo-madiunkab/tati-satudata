<?php

namespace Tests\Feature;

use App\Models\Data;
use App\Models\Opd;
use App\Models\StandarData;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PerencanaanDanPengumpulanTest extends TestCase
{
    protected function getOrCreateUserWithRole(string $roleName, int $roleId = 3): User
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

    public function test_produsen_can_only_update_jadwal_rilis_and_pemutakhiran(): void
    {
        $produsen = $this->getOrCreateUserWithRole('produsen', 3);
        $data = Data::create([
            'nama_data' => 'Data Uji Perencanaan ' . time(),
            'user_id' => $produsen->id,
            'opd_id' => $produsen->opd_id,
            'tahun' => 2026,
            'jenis_data' => 'Variabel',
            'sumber_data' => 'RPJMD',
            'sumber_referensi' => 'RPJMD',
            'status_id' => Data::STATUS_DRAFT,
        ]);

        $originalNamaData = $data->nama_data;

        $response = $this->actingAs($produsen)
            ->post('/data_produsen/update/' . $data->id, [
                'nama_data' => 'Nama Yang Harusnya Ditolak Ganti',
                'jadwal_rilis' => '2026-10-15',
                'jadwal_pemutakhiran' => '2026-11-20',
                'sumber_referensi' => 'SDSN BPS',
            ]);

        $data->refresh();

        // Nama data tetap tidak berubah untuk role Produsen
        $this->assertEquals($originalNamaData, $data->nama_data);
        // Jadwal rilis dan pemutakhiran terupdate
        $this->assertEquals('2026-10-15', $data->jadwal_rilis);
        $this->assertEquals('2026-11-20', $data->jadwal_pemutakhiran);
    }

    public function test_simpan_grid_tabular_dinamis_creates_visual_tables_and_headers(): void
    {
        $user = $this->getOrCreateUserWithRole('produsen', 3);
        $data = Data::create([
            'nama_data' => 'Data Uji Grid Tabular ' . time(),
            'user_id' => $user->id,
            'opd_id' => $user->opd_id,
            'tahun' => 2026,
            'jenis_data' => 'Variabel',
            'sumber_data' => 'RPJMD',
            'status_id' => Data::STATUS_SETUJU_STANDART_DATA,
        ]);

        $gridData = [
            'nama_tabel' => 'Tabel Indikator Kemiskinan',
            'level_data' => 'Kecamatan',
            'periode_data' => 'Tahunan',
            'headers' => ['No', 'Nama Kecamatan', 'Jumlah Penduduk Miskin (Jiwa)', 'Persentase (%)'],
            'rows' => [
                ['1', 'Kec. Mejayan', '1240', '4.2'],
                ['2', 'Kec. Dolopo', '1890', '5.1'],
                ['3', 'Kec. Balerejo', '1450', '4.8'],
            ]
        ];

        $response = $this->actingAs($user)
            ->postJson('/data_produsen/pengumpulan/' . $data->id . '/simpan-grid', $gridData);

        $response->assertStatus(200);
        $response->assertJson([
            'ok' => true,
            'message' => 'Data tabular berhasil disimpan!'
        ]);

        $data->refresh();
        $this->assertNotNull($data->data_grid_json);
        $this->assertEquals('Kecamatan', $data->level_data);
        $this->assertEquals('Tahunan', $data->periode_data);
    }

    public function test_siap_verifikasi_fails_if_metadata_incomplete(): void
    {
        $user = $this->getOrCreateUserWithRole('produsen', 3);
        $data = Data::create([
            'nama_data' => 'Data Testing Incomplete Metadata ' . time(),
            'user_id' => $user->id,
            'opd_id' => $user->opd_id,
            'tahun' => 2026,
            'jenis_data' => 'Variabel',
            'sumber_data' => 'RPJMD',
            'status_id' => Data::STATUS_SETUJU_STANDART_DATA,
        ]);

        $response = $this->actingAs($user)
            ->patchJson('/data_produsen/pengumpulan/' . $data->id . '/verifikasi');

        $response->assertStatus(200);
        $response->assertJson([
            'ok' => false,
        ]);
    }
}
