<?php

namespace App\Imports;

use App\Models\Data;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use App\Models\VisualData;
use App\Models\VisualTable;
use App\Models\VisualHeader;
use App\Models\VisualIsi;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class VisualDataImport implements ToModel, WithCalculatedFormulas
{
    private $request;
    private $visualData;
    private $tabelId;

    public function __construct($request, VisualData $visualData)
    {
        $this->request = $request;
        $this->visualData = $visualData;
        $this->tabelId = null;
    }

    public function model(array $row)
    {
        $file = $this->request->file('berkas');
        $filePath = $file->getPathname();

        $data = [];
        $judulTabel = $file->getClientOriginalName();

        $results = Excel::toArray([], $file);
        $rows = $results[0];

        $headerRow = $this->findHeaderRow($rows);

        if ($headerRow !== null) {
            $header = array_values($headerRow);
            $data = $this->extractData($rows, $headerRow);
        }

        $data = array_map(function ($row) {
            return array_map(function ($value) {
                return is_int($value) ? strval($value) : $value;
            }, $row);
        }, $data);

        // dd($judulTabel, $header, $data);

        $tabel = new VisualTable();
        $tabel->namatabel = $judulTabel;
        $tabel->id_data = $this->request->id_data;
        $tabel->save();

        $this->tabelId = $tabel->id;

        $test = [];
        foreach ($header as $index => $item) {
            $headerModel = new VisualHeader();
            $headerModel->id_namatabel = $tabel->id;
            $headerModel->header = $item;
            $headerModel->urutan_menyamping = $index;
            $headerModel->save();
            // $test[] = $headerModel;
        }
        // dd($test);

        // $coba = [];
        foreach ($data as $index => $item) {
            foreach ($item as $headerIndex => $value) {
                $headerModel = VisualHeader::where('id_namatabel', $tabel->id)
                    ->where('urutan_menyamping', $headerIndex)
                    ->first();

                if ($headerModel) {
                    $isiModel = new VisualIsi();
                    $isiModel->id_namatabel = $tabel->id;
                    $isiModel->id_header = $headerModel->id; // Menggunakan ID dari header yang sesuai
                    $isiModel->isi = isset($value) ? $value : null;
                    $isiModel->urutan_kebawah = $index;
                    $isiModel->save();
                }
            }
        }
        // dd($coba);
    }

    public function getTabelId()
    {
        return $this->tabelId;
    }

    private function findHeaderRow($rows)
    {
        foreach ($rows as $row) {
            $isHeader = true;
            foreach ($row as $value) {
                if (!is_string($value)) {
                    $isHeader = false;
                    break;
                }
            }

            if ($isHeader) {
                return $row;
            }
        }

        return null;
    }

    private function extractData($rows, $headerRow)
    {
        $data = [];
        $headerIndex = array_search($headerRow, $rows);
        $dataRows = array_slice($rows, $headerIndex + 1);

        foreach ($dataRows as $row) {
            if (!empty(array_filter($row))) {
                $data[] = array_values($row);
            }
        }

        return $data;
    }
}
