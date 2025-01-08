<?php

namespace App\Exports;

use App\Models\Data;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class DataExport implements WithMultipleSheets, FromArray, ShouldAutoSize, WithTitle, WithHeadings
{
    private Data $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new DataExport($this->data),
            new StandarDataExport($this->data->standar),
            //            strtolower($this->data->jenis_data) == 'indikator' ? new MetadataIndikatorExport($this->data->indikator) : new MetadataVariabelExport($this->data->variabel),
        ];
    }

    public function array(): array
    {
        return [
            [
                $this->data->id,
                $this->data->nama_data,
                $this->data->opd->nama_opd,
                $this->data->jenis_data,
                $this->data->sumber_data,
                $this->data->status->status,
                optional($this->data->created_at)->format('d/m/Y H:i A'),
                optional($this->data->updated_at)->format('d/m/Y H:i A'),
            ]
        ];
    }

    public function title(): string
    {
        return 'Data';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Data',
            'OPD',
            'Jenis',
            'Sumber Data',
            'Status',
            'Dibuat',
            'Diubah'
        ];
    }
}
