<?php

namespace App\Imports;

use App\Models\MetadataIndikator;
use App\Models\StandarData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MetadataIndikatorImport implements ToModel, WithMultipleSheets, WithStartRow
{
    private int $dataId;
    private string $namaData;

    public function __construct($dataId, $namaData)
    {
        $this->dataId = $dataId;
        $this->namaData = $namaData;
    }

    public function sheets(): array
    {
        return [
            new MetadataIndikatorImport($this->dataId, $this->namaData),
        ];
    }

    public function uniqueBy(): string
    {
        return 'nama';
    }

    public function model(array $row)
    {
        StandarData::updateOrCreate(
            ['data_id' => $this->dataId],
            [
                'konsep' => $row[2],
                'definisi' => $row[3],
                'klasifikasi' => $row[8],
                'satuan' => $row[7],
                'ukuran' => $row[6]
            ]
        );

        return MetadataIndikator::updateOrCreate(
            [
                'data_id' => $this->dataId
            ],
            [
                'data_id' => $this->dataId,
                'nama' => $this->namaData ?? $row[1],
                'konsep' => $row[2],
                'definisi' => $row[3],
                'interpretasi' => $row[4],
                'metode' => $row[5],
                'ukuran' => $row[6],
                'satuan' => $row[7],
                'klasifikasi_penyajian' => $row[8],
                'komposit' => $row[9] == 1 ? 1 : 0,
                'publikasi_indikator_pembangun' => $row[10],
                'nama_indikator_pembangun' => $row[11],
                'nama_variabel_pembangun' => $row[12],
                'level_estimasi' => $this->formatLevelEstimasi($row[13]),
                'umum' => $row[14] == 1 ? 1 : 0,
            ]
        );
    }

    private function formatLevelEstimasi($level): string
    {
        if (empty($level)) {
            return 'nasional';
        }

        return strtolower(trim($level));
    }

    public function startRow(): int
    {
        return 4;
    }
}
