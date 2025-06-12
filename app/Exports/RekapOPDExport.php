<?php

namespace App\Exports;

use App\Models\Data;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RekapOPDExport implements FromCollection, WithHeadings, WithTitle
{
    private Collection $data;

    private Collection $opds;

    private Collection $formattedData;

    public function __construct($data, $opds)
    {
        $this->data = $data;
        $this->opds = $opds;
        $this->format();
    }

    private function format()
    {
        $result = [];

        $i = 1;
        foreach ($this->opds as $opd) {
            $data = $this->data->where('opd_id', $opd->id);
            $result[] = [
                $i,
                $opd->nama_opd,
                $data->where('status_id', Data::STATUS_DRAFT)->sum('total') ?? 0,
                $data->where('status_id', Data::STATUS_TOLAK)->sum('total') ?? 0,
                $data->whereIn('status_id', [Data::STATUS_PENGAJUAN_STANDART_DATA, Data::STATUS_SETUJU, Data::STATUS_REVISI_STANDART_DATA])->sum('total') ?? 0,
                $data->where('status_id', Data::STATUS_SETUJU_STANDART_DATA)->sum('total') ?? '0',
                $data->where('status_id', Data::STATUS_PROSES_VERIFIKASI)->sum('total') ?? 0,
                $data->where('status_id', Data::STATUS_REVISI)->sum('total') ?? 0,
                $data->where('status_id', Data::STATUS_SIAP_PUBLIKASI)->sum('total') ?? 0,
                $data->where('status_id', Data::STATUS_TERPUBLIKASI)->sum('total') ?? 0,
                $data->where('status_id', Data::STATUS_DRAFT)
                    ->sum('total') + $data->where('status_id', Data::STATUS_TOLAK)
                    ->sum('total') + $data->whereIn('status_id', [Data::STATUS_PENGAJUAN_STANDART_DATA, Data::STATUS_SETUJU, Data::STATUS_REVISI_STANDART_DATA])
                    ->sum('total') + $data->where('status_id', Data::STATUS_SETUJU_STANDART_DATA)
                    ->sum('total') + $data->where('status_id', Data::STATUS_PROSES_VERIFIKASI)
                    ->sum('total') + $data->where('status_id', Data::STATUS_REVISI)
                    ->sum('total') + $data->where('status_id', Data::STATUS_SIAP_PUBLIKASI)
                    ->sum('total') + $data->where('status_id', Data::STATUS_TERPUBLIKASI)
                    ->sum('total'),
            ];
            $i++;
        }

        $this->formattedData = collect($result);
    }

    public function collection()
    {
        return $this->formattedData;
    }

    public function title(): string
    {
        return 'Data';
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nama OPD',
            'Draft',
            'Ditolak',
            'Proses Standar Data',
            'Pengumpulan',
            'Verifikasi',
            'Revisi',
            'Siap Publikasi',
            'Terpublikasi',
            'Total',
        ];
    }
}
