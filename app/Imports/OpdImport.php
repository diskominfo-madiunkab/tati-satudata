<?php

namespace App\Imports;

use App\Models\Opd;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use RealRashid\SweetAlert\Facades\Alert;

HeadingRowFormatter::default('none');

class OpdImport implements ToModel, WithHeadingRow

{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    // HeadingRowFormatter::default('none');
    public function model(array $row)
    {
        $opd = Opd::create([
            'nama_opd'     => $row['Nama OPD'],
            'nip_penjabat'     => $row['Nip Penjabat'],
            'nama_penjabat'     => $row['Nama Penjabat'],
            'pangkat_penjabat'     => $row['Pangkat Penjabat'],
            'jabatan_penjabat'     => $row['Jabatan Penjabat'],
        ]);
        // dd($opd);
        Alert::success('Berhasil', 'Berhasil Menambahkan Data Dari Excel!');
        activity()->performedOn($opd)->log('Import OPD');
    }
}
