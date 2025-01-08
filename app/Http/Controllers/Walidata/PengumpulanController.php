<?php

namespace App\Http\Controllers\Walidata;

use App\Exports\DataExport;
use App\Http\Controllers\Controller;
use App\Imports\MetadataIndikatorImport;
use App\Imports\MetadataVariabelImport;
use App\Models\Berkas;
use App\Models\Data;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\Province;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class PengumpulanController extends Controller
{
    public function indikator($id)
    {
        $data = Data::with(['indikator', 'standar'])->findOrFail($id);

        if (in_array($data->status_id, [Data::STATUS_REVISI, Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])) {
            $data->load('verifikasi');
        }
        return view('pages.contents.walidata.pengumpulan.form-indikator', compact('data'));
    }

    public function variabel($id)
    {
        $data = Data::with(['variabel', 'standar'])
            ->findOrFail($id);

        if (in_array($data->status_id, [Data::STATUS_REVISI, Data::STATUS_PROSES_VERIFIKASI, Data::STATUS_SIAP_PUBLIKASI, Data::STATUS_TERPUBLIKASI])) {
            $data->load('verifikasi');
        }

        return view('pages.contents.walidata.pengumpulan.form-variabel', compact('data'));
    }
}
