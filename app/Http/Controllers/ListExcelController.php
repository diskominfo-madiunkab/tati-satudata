<?php

namespace App\Http\Controllers;

use App\Exports\ListExcelExport;
use App\Models\Berkas;
use App\Models\Opd;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ListExcelController extends Controller
{
    public function index()
    {
        $data = Berkas::query()
            ->with(['data.opd'])
            ->get();

        return Excel::download(new ListExcelExport($data), 'list-excel-upload-ulang.xlsx');
    }
}
