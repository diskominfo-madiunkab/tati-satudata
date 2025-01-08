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

class MetadataVariabelExport implements FromArray, ShouldAutoSize, WithTitle, WithHeadings
{
    private $variabel;

    public function __construct($variabel)
    {
        $this->variabel = $variabel;
    }

    public function array(): array
    {
        return [
            [
                $this->variabel->nama,
                $this->variabel->alias,
                $this->variabel->konsep,
                $this->variabel->definisi,
                $this->variabel->referensi_pemilihan,
                $this->variabel->referensi_waktu,
                ucfirst($this->variabel->tipe_data),
                $this->variabel->ukuran,
                $this->variabel->satuan,
                $this->variabel->aturan_validasi,
                $this->variabel->kalimat_pertanyaan,
                $this->variabel->umum == 1 ? 'Ya' : 'Tidak',
            ]
        ];
    }

    public function title(): string
    {
        return 'Metadata Variabel';
    }

    public function headings(): array
    {
        return [
            'Nama Variabel',
            'Alias',
            'Konsep',
            'Definisi',
            'Referensi Pemilihan',
            'Referensi Waktu',
            'Tipe Data',
            'Ukuran',
            'Satuan',
            'Aturan Validasi',
            'Kalimat Pertanyaan',
            'Apakah Kolom Dapat Diakses Umum?'
        ];
    }

}
