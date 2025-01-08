<?php

namespace App\Exports;

class IndikatorExport extends MetadataExport
{
    protected string $type = 'indikator';

    protected function templatePath(): string
    {
        return storage_path('app/templates/MS-Indikator.xlsx');
    }

    protected function mappedCells(): array
    {
        return [
            'A21' => 1,
            'B21' => $this->metadata->nama,
            'F21' => $this->metadata->konsep,
            'I21' => $this->metadata->definisi,
            'N21' => $this->metadata->interpretasi,
            'S21' => $this->metadata->metode,
            'X21' => $this->metadata->ukuran,
            'AA21' => $this->metadata->satuan,
            'AD21' => $this->metadata->klasifikasi_penyajian,
            'AG21' => intval($this->metadata->komposit) == 1 ? 1 : 2,
            'AJ21' => $this->metadata->publikasi_indikator_pembangun,
            'AM21' => $this->metadata->nama_indikator_pembangun,
            'AV21' => $this->metadata->nama_variabel_pembangun,
            'AY21' => ucwords($this->metadata->level_estimasi),
            'BB21' => $this->metadata->umum,

            'AO10' => $this->opd->nama_opd,
        ];
    }
}
