<?php

namespace App\Imports;

use App\Models\Data;
use App\Models\Opd;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use RealRashid\SweetAlert\Facades\Alert;

HeadingRowFormatter::default('none');

class DataImport implements ToModel, WithHeadingRow, WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new DataImport,
        ];
    }

    public function model(array $row)
    {
        if (empty($row['Nama Data'])) {
            return null;
        }

        if (! isset($row['OPD'])) {
            throw new \Exception('Data tidak valid');
            // Alert::error('error', 'Data tidak valid');
        }

        $cek_opd = Opd::select('id')->where('nama_opd', '=', $row['OPD'])->first();

        if (! $cek_opd) {
            // Alert::error('error', 'OPD ' . $row['OPD']  . ' tidak ditemukan');
            throw new \Exception('OPD '.$row['OPD'].' tidak ditemukan');
        }

        $existingData = Data::where('opd_id', $cek_opd->id)->where('nama_data', $row['Nama Data'])->where('tahun', $row['Tahun'])->first();
        // dd($existingData);
        if ($existingData) {
            // Alert::error('error', 'Data dengan nama ' . $row['Nama Data']  . '  sudah terdapat pada sistem');
            throw new \Exception('Data dengan nama '.$row['Nama Data'].'  sudah terdapat pada sistem');
        }
        $jadwalRilis = Carbon::createFromFormat('d-m-Y', $row['Jadwal Rilis'])->format('Y-m-d');
        if ($row['Data Prioritas'] === 'Ya') {
            $dataPrioritas = 1;
        } else {
            $dataPrioritas = 0;
        }
        $data = Data::create([
            'nama_data' => $row['Nama Data'],
            'opd_id' => $cek_opd->id,
            'jenis_data' => $row['Jenis Data'],
            'sumber_data' => $row['Sumber data'],
            'tahun' => $row['Tahun'],
            // 'jadwal_rilis'     => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['Jadwal Rilis']),
            'jadwal_rilis' => $jadwalRilis,
            'jadwal_pemutakhiran' => $row['Jadwal Pemutakhiran'],
            'data_prioritas' => $dataPrioritas,
            'status_id' => Data::STATUS_DRAFT,
            'user_id' => auth()->id(),

        ]);

        // dd($data);

        activity()
            ->causedBy(auth()->id())
            ->performedOn($data)
            ->log('mengimport data');

        return $data;
    }
}
