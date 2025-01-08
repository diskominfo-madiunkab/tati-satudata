<?php

namespace App\Imports;

use App\Models\MetadataVariabel;
use App\Models\StandarData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class MetadataVariabelImport implements ToModel, WithMultipleSheets, WithStartRow
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
            new MetadataVariabelImport($this->dataId, $this->namaData),
        ];
    }

    public function model(array $row)
    {
        StandarData::updateOrCreate(
            ['data_id' => $this->dataId],
            [
                'konsep' => $row[3],
                'definisi' => $row[4],
                'klasifikasi' => $row[10],
                'satuan' => $row[9],
                'ukuran' => $row[8]
            ]
        );

        return MetadataVariabel::updateOrCreate(
            [
                'data_id' => $this->dataId
            ],
            [
                'data_id' => $this->dataId,
                'nama' => $this->namaData,
                'alias' => $row[2],
                'konsep' => $row[3],
                'definisi' => $row[4],
                'referensi_pemilihan' => $row[5],
                'referensi_waktu' => $row[6],
                'tipe_data' => strtolower($row[7] ?? ''),
                'ukuran' => $row[8],
                'satuan' => $row[9],
                'klasifikasi_isian' => $row[10],
                'aturan_validasi' => $row[11],
                'kalimat_pertanyaan' => $row[12],
                'umum' => $row[13] ? ($row[13] == 1 ? 1 : 0) : 0
            ]
        );
    }

    public function uniqueBy(): string
    {
        return 'nama';
    }

    public function startRow(): int
    {
        return 3;
    }
}
