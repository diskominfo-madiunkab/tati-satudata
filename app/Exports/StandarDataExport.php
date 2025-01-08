<?php

namespace App\Exports;

use App\Models\StandarData;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class StandarDataExport implements FromArray, WithTitle, ShouldAutoSize, WithHeadings
{
    private $standar;

    public function __construct($standar)
    {
        $this->standar = $standar;
    }

    public function array(): array
    {
        return [
            [
                $this->standar->konsep,
                $this->standar->definisi,
                $this->standar->ukuran,
                $this->standar->satuan,
                $this->standar->klasifikasi
            ]
        ];
    }

    public function title(): string
    {
        return 'Standar Data';
    }

    public function headings(): array
    {
        return [
            'Konsep',
            'Definisi',
            'Ukuran',
            'Satuan',
            'Klasifikasi',
        ];
    }
}
