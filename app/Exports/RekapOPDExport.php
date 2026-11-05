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
            $dDraft = $data->where('status_id', Data::STATUS_DRAFT)->sum('total') ?? 0;
            $dTolak = $data->where('status_id', Data::STATUS_TOLAK)->sum('total') ?? 0;
            $dStandar = $data->whereIn('status_id', [Data::STATUS_PENGAJUAN_STANDART_DATA, Data::STATUS_SETUJU, Data::STATUS_REVISI_STANDART_DATA])->sum('total') ?? 0;
            $dPengumpulan = $data->where('status_id', Data::STATUS_SETUJU_STANDART_DATA)->sum('total') ?? 0;
            $dVerifikasi = $data->where('status_id', Data::STATUS_PROSES_VERIFIKASI)->sum('total') ?? 0;
            $dRevisi = $data->where('status_id', Data::STATUS_REVISI)->sum('total') ?? 0;
            $dSiapPublikasi = $data->where('status_id', Data::STATUS_SIAP_PUBLIKASI)->sum('total') ?? 0;
            $dTerpublikasi = $data->where('status_id', Data::STATUS_TERPUBLIKASI)->sum('total') ?? 0;
            $dTotal = $dDraft + $dTolak + $dStandar + $dPengumpulan + $dVerifikasi + $dRevisi + $dSiapPublikasi + $dTerpublikasi;

            $dNonTolak = $dTotal - $dTolak;
            $persenKeterisian = $dNonTolak > 0 ? round((($dVerifikasi + $dRevisi + $dSiapPublikasi + $dTerpublikasi) / $dNonTolak) * 100, 1) . '%' : '0%';
            $persenValid = $dNonTolak > 0 ? round((($dSiapPublikasi + $dTerpublikasi) / $dNonTolak) * 100, 1) . '%' : '0%';
            $persenTerpublikasi = $dNonTolak > 0 ? round(($dTerpublikasi / $dNonTolak) * 100, 1) . '%' : '0%';

            $result[] = [
                $i,
                $opd->nama_opd,
                $dDraft,
                $dTolak,
                $dStandar,
                $dPengumpulan,
                $dVerifikasi,
                $dRevisi,
                $dSiapPublikasi,
                $dTerpublikasi,
                $dTotal,
                $persenKeterisian,
                $persenValid,
                $persenTerpublikasi,
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
            '% Keterisian Data',
            '% Data Valid',
            '% Data Terpublikasi',
        ];
    }
}
