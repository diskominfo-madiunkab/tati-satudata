<?php

namespace App\Exports;

class VariabelExport extends MetadataExport
{
    protected string $type = 'variabel';

    protected $standarData;

    protected function templatePath(): string
    {
        return storage_path('app/templates/MS-Variabel.xlsx');
    }

    public function standarData($data)
    {
        $this->standarData = $data;
    }

    protected function mappedCells(): array
    {
        return [
            'A21' => 1,
            'B21' => $this->metadata->nama,
            'G21' => $this->metadata->alias,
            'K21' => $this->metadata->konsep,
            'P21' => $this->metadata->definisi,
            'Y21' => $this->metadata->referensi_pemilihan,
            'AC21' => $this->metadata->referensi_waktu ?? '-',
            'AG21' => ucfirst($this->metadata->tipe_data ?? '-'),
            'AK21' => $this->standarData->klasifikasi ?? '-',
            'AO21' => $this->metadata->aturan_validasi,
            'AS21' => $this->metadata->kalimat_pertanyaan,
            'BB21' => $this->metadata->umum,

            'AO10' => $this->opd->nama_opd,
        ];
    }
}
