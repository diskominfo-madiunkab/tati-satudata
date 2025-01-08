<?php

namespace App\Exports;

use App\Models\MetadataIndikator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\BaseDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MetadataIndikatorExport implements FromArray, ShouldAutoSize, WithTitle, WithHeadings
{
    private $indikator;

    public function __construct($indikator)
    {
        $this->indikator = $indikator;
    }

    public function array(): array
    {
        return [
            [
                $this->indikator->nama,
                $this->indikator->konsep,
                $this->indikator->definisi,
                $this->indikator->interpretasi,
                $this->indikator->metode,
                $this->indikator->ukuran,
                $this->indikator->satuan,
                $this->indikator->klasifikasi_penyajian,
                $this->indikator->komposit == 1 ? 'Ya' : 'Tidak',
                $this->indikator->publikasi_indikator_pembangun,
                $this->indikator->nama_indikator_pembangun,
                $this->indikator->nama_variabel_pembangun,
                strtoupper($this->indikator->level_estimasi ?? ''),
                $this->indikator->umum == 1 ? 'Ya' : 'Tidak',
            ]
        ];
    }

    public function title(): string
    {
        return 'Metadata Indikator';
    }

    public function headings(): array
    {
        return [
            'Nama Indikator',
            'Konsep',
            'Definisi',
            'Interpretasi',
            'Metode/Rumus Perhitungan',
            'Ukuran',
            'Satuan',
            'Klasifikasi Penyajian',
            'Apakah Kolom Indikator Komposit?',
            'Indikator Pembangun',
            'Publikasi Ketersediaan',
            'Nama Indikator Pembangun',
            'Nama Variabel Pembangun',
            'Level Estimasi',
            'Apakah Kolom Dapat Diakses Umum?'
        ];
    }

}
