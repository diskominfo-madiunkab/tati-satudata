<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class ListExcelExport implements WithMultipleSheets, FromCollection, ShouldAutoSize, WithTitle, WithHeadings, WithMapping
{
    private  $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new ListExcelExport($this->data),
            //            strtolower($this->data->jenis_data) == 'indikator' ? new MetadataIndikatorExport($this->data->indikator) : new MetadataVariabelExport($this->data->variabel),
        ];
    }

    public function collection()
    {
        return $this->data;
    }

    public function map($berkas): array
    {
        return [
            $berkas->name,
            $berkas->data->opd->nama_opd,
            $berkas->tahun,
            $berkas->created_at
        ];
    }

    public function title(): string
    {
        return 'List Excel Upload Ulang';
    }

    public function headings(): array
    {
        return [
            'Nama Excel',
            'OPD',
            'Tahun',
            'Tanggal Upload',
        ];
    }
}
