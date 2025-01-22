<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExcelExport implements WithMultipleSheets, FromArray, ShouldAutoSize, WithTitle, WithHeadings, WithMapping
{
    private  $data;
    private $title;
    private array $listHeader;

    public function __construct($data, $title, array $listHeader)
    {
        $this->data = $data;
        $this->title = $title;
        $this->listHeader = $listHeader;
    }

    public function sheets(): array
    {
        return [
            new ExcelExport($this->data, $this->title, $this->listHeader),
            //            strtolower($this->data->jenis_data) == 'indikator' ? new MetadataIndikatorExport($this->data->indikator) : new MetadataVariabelExport($this->data->variabel),
        ];
    }

    public function array(): array
    {
        return $this->data;
    }

    public function map($data): array
    {
        $mapped = [];
        foreach ($data as $key => $value) {
            $mapped[] = $data[$key];
        }
        return $mapped;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function headings(): array
    {
        return $this->listHeader;
    }
}
