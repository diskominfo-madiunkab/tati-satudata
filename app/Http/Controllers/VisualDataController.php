<?php

namespace App\Http\Controllers;

use App\Imports\VisualDataImport;
use App\Models\Berkas;
use App\Models\Data;
use App\Models\GrafikData;
use App\Models\Verifikasi;
use App\Models\VisualData;
use App\Models\VisualHeader;
use App\Models\VisualIsi;
use App\Models\VisualTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\IOFactory;

class VisualDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    // public function store(Request $request, VisualData $visualData)
    // {
    //     // Validasi file Excel di sini jika diperlukan
    //     // Panggil fungsi import dengan menggunakan VisualDataImport
    //     // $import = new VisualDataImport($request, $visualData);
    //     // // dd($import);

    //     // Excel::import($import, $request->file('berkas'));

    //     $data = Data::when(auth()->user()->hasAnyRole('produsen'), fn ($q) => $q->where('opd_id', auth()->user()->opd_id))->findOrFail($request->id_data);

    //     if ($data->status_id != Data::STATUS_SETUJU && $data->status_id != Data::STATUS_REVISI) {
    //         return redirect()->back()->withErrors('Data tidak valid.')->withInput();
    //     }


    //     $fileName = $request->file('berkas')->getClientOriginalName();
    //     $storedPath = $request->file('berkas')->storeAs('public/exports/' . Str::slug($data->nama_data), $fileName);

    //     if (!$storedPath) {
    //         return response([], 500);
    //     }

    //     $berkas = $data->berkas()->create([
    //         'visual_id' => $import->getTabelId(),
    //         'tahun' => $data->tahun,
    //         'name' => $fileName,
    //         'size' => Storage::size($storedPath),
    //         'path' => $storedPath
    //     ]);

    //     Alert::success('Berhasil', 'Berhasil Menambah Data!');
    //     return redirect()->back();
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, VisualData $visualData)
    {
        $request->validate([
            'berkas' => 'mimes:xlsx'
        ]);
        try {
            DB::beginTransaction();
            $file = $request->file('berkas');
            $fileExtension = $file->getClientOriginalExtension();

            // Save the original file first
            $data = Data::with(['berkas.visualTable'])->when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))->findOrFail($request->id_data);
            $originalFileName = $file->getClientOriginalName();
            $originalStoredPath = $file->storeAs('public/exports/' . Str::slug($data->nama_data) . '/' . $data->tahun, $originalFileName);

            if (!$originalStoredPath) {
                return response([], 500);
            }

            // Check if the original file exists in the specified storage path
            if (!Storage::exists($originalStoredPath)) {
                return response([], 500);
            }

            if ($data->berkas->count() > 0) {
                foreach ($data->berkas as $berkasToDelete) {
                    if ($berkasToDelete->visualTable) {
                        $berkasToDelete->visualTable->header()->delete();
                        $berkasToDelete->visualTable->isi()->delete();
                        $berkasToDelete->visualTable->delete();
                    }
                    if (Storage::exists($berkasToDelete->path)) {
                        Storage::delete($berkasToDelete->path);
                    }
                    $berkasToDelete->delete();
                }
            }

            $originalFileSize = Storage::size($originalStoredPath);
            $originalFileUrl = Storage::url($originalStoredPath);

            // Update the value_sipd column in the data model
            $data->update([
                'value_sipd' => $request->value_sipd,
            ]);

            // Create a new record in the database for the uploaded original file
            $berkas = $data->berkas()->create([
                'tahun' => $data->tahun,
                'name' => $originalFileName,
                'size' => $originalFileSize,
                'path' => $originalStoredPath,
                'visual_id' => null // Placeholder, will be updated later if needed
            ]);

            // Convert XLSX to CSV if necessary
            if ($fileExtension == 'xlsx') {
                $filePath = $file->getPathname();
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
                $csvWriter = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);

                // Save the CSV to a temporary path
                $csvPath = tempnam(sys_get_temp_dir(), 'csv');
                $csvWriter->save($csvPath);

                // Store the CSV file in the desired location
                $csvFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '.csv';
                $csvStoredPath = 'public/exports/' . Str::slug($data->nama_data) . '/' . $data->tahun . '/'  . $csvFileName;
                Storage::putFileAs('public/exports/' . Str::slug($data->nama_data) . '/' . $data->tahun . '/', new \Illuminate\Http\File($csvPath), $csvFileName);

                if (!Storage::exists($csvStoredPath)) {
                    return response([], 500);
                }

                $csvFileSize = Storage::size($csvStoredPath);
                $csvFileUrl = Storage::url($csvStoredPath);

                // Create a new record in the database for the uploaded CSV file
                $berkasCsv = $data->berkas()->create([
                    'tahun' => $data->tahun,
                    'name' => $csvFileName,
                    'size' => $csvFileSize,
                    'path' => $csvStoredPath,
                    'visual_id' => null // Placeholder, will be updated later if needed
                ]);

                // Clean up the temporary file
                unlink($csvPath);

                // Process the data from the original XLSX file
                $results = Excel::toArray([], $file);
                $rows = $results[0];

                // Get table title from the topmost column
                $judulTabel = $data->nama_data;
                $headerRow = $this->findHeaderRow($rows);

                if ($headerRow !== null) {
                    $header = array_values($headerRow);
                    $dataRows = $this->extractData($rows, $headerRow);

                    // Convert array to string format
                    $dataRows = array_map(function ($row) {
                        return array_map(function ($value) {
                            return is_int($value) ? strval($value) : $value;
                        }, $row);
                    }, $dataRows);

                    // Save table data
                    $tabel = new VisualTable();
                    $tabel->namatabel = $judulTabel;
                    $tabel->id_data = $request->id_data;
                    $tabel->save();

                    // Save header data
                    foreach ($header as $index => $item) {
                        $headerModel = new VisualHeader();
                        $headerModel->id_namatabel = $tabel->id;
                        $headerModel->header = $item;
                        $headerModel->urutan_menyamping = $index;
                        $headerModel->save();
                    }

                    // Save content data
                    foreach ($dataRows as $index => $item) {
                        foreach ($item as $headerIndex => $value) {
                            $headerModel = VisualHeader::where('id_namatabel', $tabel->id)
                                ->where('urutan_menyamping', $headerIndex)
                                ->first();

                            if ($headerModel) {
                                $isiModel = new VisualIsi();
                                $isiModel->id_namatabel = $tabel->id;
                                $isiModel->id_header = $headerModel->id;
                                $isiModel->isi = $value ?? null;
                                $isiModel->urutan_kebawah = $index;
                                $isiModel->save();
                            }
                        }
                    }

                    // Update visual_id in berkas and berkasCsv
                    $berkas->update(['visual_id' => $tabel->id]);
                }
            }

            DB::commit();
            Alert::success('Berhasil', 'Berhasil Menambah Data!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Gagal Upload Data!', 'Pastikan template excel yang anda gunakan sudah benar!');
            return redirect()->back();
        }
    }

    public function store_old(Request $request, VisualData $visualData)
    {
        try {
            DB::beginTransaction();
            $file = $request->file('berkas');
            $fileExtension = $file->getClientOriginalExtension();

            // Save the original file first
            $data = Data::when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))->findOrFail($request->id_data);
            $originalFileName = $file->getClientOriginalName();
            $originalStoredPath = $file->storeAs('public/exports/' . Str::slug($data->nama_data), $originalFileName);

            if (!$originalStoredPath) {
                return response([], 500);
            }

            // Check if the original file exists in the specified storage path
            if (!Storage::exists($originalStoredPath)) {
                return response([], 500);
            }

            $originalFileSize = Storage::size($originalStoredPath);
            $originalFileUrl = Storage::url($originalStoredPath);

            // Update the value_sipd column in the data model
            $data->update([
                'value_sipd' => $request->value_sipd,
            ]);

            // Create a new record in the database for the uploaded original file
            $berkas = $data->berkas()->create([
                'tahun' => $data->tahun,
                'name' => $originalFileName,
                'size' => $originalFileSize,
                'path' => $originalStoredPath
            ]);

            // Convert XLSX to CSV if necessary
            if ($fileExtension == 'xlsx') {
                $filePath = $file->getPathname();
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
                $csvWriter = new \PhpOffice\PhpSpreadsheet\Writer\Csv($spreadsheet);

                // Save the CSV to a temporary path
                $csvPath = tempnam(sys_get_temp_dir(), 'csv');
                $csvWriter->save($csvPath);

                // Store the CSV file in the desired location
                $csvFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '.csv';
                $csvStoredPath = 'public/exports/' . Str::slug($data->nama_data) . '/' . $csvFileName;
                Storage::putFileAs('public/exports/' . Str::slug($data->nama_data), new \Illuminate\Http\File($csvPath), $csvFileName);

                if (!Storage::exists($csvStoredPath)) {
                    return response([], 500);
                }

                $csvFileSize = Storage::size($csvStoredPath);
                $csvFileUrl = Storage::url($csvStoredPath);

                // Create a new record in the database for the uploaded CSV file
                $berkasCsv = $data->berkas()->create([
                    'tahun' => $data->tahun,
                    'name' => $csvFileName,
                    'size' => $csvFileSize,
                    'path' => $csvStoredPath
                ]);

                // Clean up the temporary file
                unlink($csvPath);

                // Process the data from the original XLSX file
                $results = Excel::toArray([], $file);
                $rows = $results[0];

                // Get table title from the topmost column
                $judulTabel = !empty($rows) ? $rows[0][0] : $request->file('berkas')->getClientOriginalName();
                $headerRow = $this->findHeaderRow($rows);

                if ($headerRow !== null) {
                    $header = array_values($headerRow);
                    $dataRows = $this->extractData($rows, $headerRow);

                    // Convert array to string format
                    $dataRows = array_map(function ($row) {
                        return array_map(function ($value) {
                            return is_int($value) ? strval($value) : $value;
                        }, $row);
                    }, $dataRows);

                    // Save table data
                    $tabel = new VisualTable();
                    $tabel->namatabel = $judulTabel;
                    $tabel->id_data = $request->id_data;
                    $tabel->save();

                    // Save header data
                    foreach ($header as $index => $item) {
                        $headerModel = new VisualHeader();
                        $headerModel->id_namatabel = $tabel->id;
                        $headerModel->header = $item;
                        $headerModel->urutan_menyamping = $index;
                        $headerModel->save();
                    }

                    // Save content data
                    foreach ($dataRows as $index => $item) {
                        foreach ($item as $headerIndex => $value) {
                            $headerModel = VisualHeader::where('id_namatabel', $tabel->id)
                                ->where('urutan_menyamping', $headerIndex)
                                ->first();

                            if ($headerModel) {
                                $isiModel = new VisualIsi();
                                $isiModel->id_namatabel = $tabel->id;
                                $isiModel->id_header = $headerModel->id;
                                $isiModel->isi = $value ?? null;
                                $isiModel->urutan_kebawah = $index;
                                $isiModel->save();
                            }
                        }
                    }
                }
            }

            DB::commit();
            Alert::success('Berhasil', 'Berhasil Menambah Data!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Gagal Upload Data!', 'Pastikan template excel yang anda gunakan sudah benar!');
            return redirect()->back();
        }
    }


    public function store2(Request $request, VisualData $visualData)
    {
        $request->validate([
            'berkas' => 'mimes:xlsx'
        ]);
        try {
            DB::beginTransaction();
            $file = $request->file('berkas');
            $filePath = $file->getPathname();
            $fileExtension = $file->getClientOriginalExtension();
            // dd($fileExtension);
            if ($fileExtension == 'csv') {
                // dd('siniiii');
                $data = Data::when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))->findOrFail($request->id_data);
                $fileName = $request->file('berkas')->getClientOriginalName();
                $storedPath = $request->file('berkas')->storeAs('public/exports/' . Str::slug($data->nama_data), $fileName);
                if (!$storedPath) {
                    return response([], 500);
                }
                // Check if the file exists in the specified storage path
                if (Storage::exists($storedPath)) {
                    // dd($data->berkas());
                    $fileSize = Storage::size($storedPath);
                    $fileUrl = Storage::url($storedPath);
                    // Create a new record in the database for the uploaded file
                    $berkas = $data->berkas()->create([
                        'tahun' => $data->tahun,
                        'name' => $fileName,
                        'size' => $fileSize,
                        'path' => $storedPath
                    ]);
                    // for mentalne kalo gagal
                    DB::commit();
                    Alert::success('Berhasil', 'Berhasil Menambah Data!');
                    return redirect()->back();
                } else {
                    // File upload failed
                    // Handle the error condition here
                    return response([], 500);
                }
            }

            $data = [];
            $judulTabel = $request->file('berkas')->getClientOriginalName();

            $results = Excel::toArray([], $file);
            $rows = $results[0];

            // Ambil judul tabel dari kolom paling atas
            if (!empty($rows)) {
                $judulTabel = $rows[0][0];
            }


            $headerRow = $this->findHeaderRow($rows);
            // dd($headerRow);

            if ($headerRow !== null) {
                $header = array_values($headerRow);
                $data = $this->extractData($rows, $headerRow);
            }
            // Ubah array ke dalam bentuk string
            $data = array_map(function ($row) {
                return array_map(function ($value) {
                    return is_int($value) ? strval($value) : $value;
                }, $row);
            }, $data);

            // Simpan data tabel
            $tabel = new VisualTable();
            $tabel->namatabel = $judulTabel; // Menggunakan variabel $judul yang telah diberikan
            $tabel->id_data = $request->id_data; // Menggunakan variabel $judul yang telah diberikan
            $tabel->save();

            // Simpan data header
            foreach ($header as $index => $item) {
                $headerModel = new VisualHeader();
                $headerModel->id_namatabel = $tabel->id; // Menggunakan ID tabel yang baru saja disimpan
                $headerModel->header = $item;
                $headerModel->urutan_menyamping = $index;
                $headerModel->save();
            }

            // Simpan data isi
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
            $data = Data::when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))->findOrFail($request->id_data);

            $fileName = $request->file('berkas')->getClientOriginalName();
            $storedPath = $request->file('berkas')->storeAs('public/exports/' . Str::slug($data->nama_data), $fileName);

            if (!$storedPath) {
                return response([], 500);
            }
            // dd($data->tahun, $fileName, Storage::size($storedPath), $storedPath);


            $berkas = $data->berkas()->create([
                'visual_id' => $tabel->id,
                'tahun' => $data->tahun,
                'name' => $fileName,
                'size' => Storage::size($storedPath),
                'path' => $storedPath
            ]);
            // dd($berkas);


            DB::commit(); // Commit database transaction jika tidak ada kesalahan
            Alert::success('Berhasil', 'Berhasil Menambah Data!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback(); // Rollback database transaction jika terjadi kesalahan
            Alert::error('Gagal Upload Data!', 'Pastikan template excel yang anda gunakan sudah benar!');
            return redirect()->back();
        }
    }
    public function updateCell(Request $request)
    {
        $tableId = $request->input('table_id');
        $rowIndex = $request->input('row_index');
        $columnIndex = $request->input('column_index');
        $cellValue = $request->input('cell_value');

        // Find the corresponding data row
        $row = VisualIsi::where('id_namatabel', $tableId)
            ->where('urutan_kebawah', $rowIndex)
            ->first();

        if ($row) {
            // Update the cell value
            $row->update([
                'isi' => $cellValue
            ]);

            return response()->json(['message' => 'Cell updated successfully']);
        } else {
            return response()->json(['error' => 'Row not found'], 404);
        }
    }

    public function deleteRow(Request $request)
    {
        $tableId = $request->input('table_id');
        $rowIndex = $request->input('row_index');

        // Find the corresponding data row
        $row = VisualIsi::where('id_namatabel', $tableId)
            ->where('urutan_kebawah', $rowIndex)
            ->first();

        if ($row) {
            // Delete the row
            $row->delete();

            return response()->json(['message' => 'Row deleted successfully']);
        } else {
            return response()->json(['error' => 'Row not found'], 404);
        }
    }


    private function findHeaderRow($rows)
    {
        foreach ($rows as $row) {
            // Tambahkan kondisi yang sesuai untuk mengidentifikasi baris header
            // Berdasarkan pola atau kondisi yang ada pada nilai-nilai dalam baris tersebut
            // Contoh: periksa apakah semua nilai di baris ini adalah string atau tidak
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


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VisualData  $visualData
     * @return \Illuminate\Http\Response
     */
    public function show(VisualData $visualData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VisualData  $visualData
     * @return \Illuminate\Http\Response
     */
    public function edit(VisualData $visualData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VisualData  $visualData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        VisualData::where('id', $request->id)->update([
            // 'tahun'     => $request->tahun,
            'nilai'   => $request->nilai
        ]);
        Alert::success('Berhasil', 'Data Berhasil Diperbarui!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VisualData  $visualData
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        $berkasId = $request->id_berkas;
        $visualDataId = $request->id_visualdata;
        $dataId = $request->id_data;
        $tahun = $request->tahunhapus;
        // dd($berkasId, $visualDataId, $dataId, $tahun);

        // // hapus data pada tabel VisualData dengan id tertentu
        // VisualTable::where('id', $visualDataId)->delete();
        if ($visualDataId != null) {
            // Hapus data dari tabel utama
            $mainTable = VisualTable::findOrFail($visualDataId);
            $mainTable->delete();

            // Hapus semua foreign key di tabel terkait pertama
            VisualHeader::where('id_namatabel', $mainTable->id)->delete();

            // Hapus semua foreign key di tabel terkait kedua
            VisualIsi::where('id_namatabel', $mainTable->id)->delete();

            // hapus data pada tabel Berkas dengan data_id dan tahun tertentu
            Berkas::where('id', $berkasId)->where('data_id', $dataId)->where('tahun', $tahun)->where('visual_id', $visualDataId)->delete();
            //    hapus grafik
            GrafikData::where('id_table', $mainTable->id)->delete();
            Verifikasi::where('data_id', $dataId)->where('field', $berkasId)->delete();
        } else {
            Berkas::where('id', $berkasId)->where('data_id', $dataId)->where('tahun', $tahun)->delete();
            Verifikasi::where('data_id', $dataId)->where('field', $berkasId)->delete();
        }
        // tampilkan pesan sukses dan redirect ke halaman sebelumnya
        Alert::success('Berhasil', 'Data Berhasil Dihapus!');
        return redirect()->back();
    }

    public function destroy_berkas(Request $request)
    {
        // VisualData::where('id', $request->id)->delete();
        Berkas::where('id', $request->id_hapus_berkas)->delete();
        Alert::success('Berhasil', 'Data Berhasil Dihapus!');
        return redirect()->back();
    }

    public function upload(Request $request)
    {
        dd('test');
        $request->validate([
            'berkas' => 'required|file'
        ]);

        // dd($request->berkas);
        // Excel::import(new VisualDataImport, request()->file('berkas'));
        $file = $request->file('berkas');

        // Mendapatkan semua baris dari file Excel
        $rows = Excel::toArray([], $file);

        // Menentukan baris awal yang berisi data
        $startRow = null;
        foreach ($rows as $rowKey => $row) {
            $hasData = false;

            foreach ($row as $columnKey => $cell) {
                if (!empty($cell)) {
                    $hasData = true;
                    break;
                }
            }

            if ($hasData) {
                $startRow = $rowKey;
                break;
            }
        }

        // Jika tidak ada data yang ditemukan, tangani sesuai kebutuhan
        if (
            $startRow === null
        ) {
            // ...
        }

        // Mendapatkan baris judul
        $judulRow = $startRow - 1;

        // Menentukan baris header
        $headerRow = null;
        foreach ($rows as $rowKey => $row) {
            if ($rowKey > $startRow && !empty($row)) {
                $headerRow = $rowKey;
                break;
            }
        }

        // Jika tidak ada baris header yang ditemukan, tangani sesuai kebutuhan
        if ($headerRow === null) {
            // ...
        }

        // Mendapatkan header
        $header = $rows[$headerRow];

        // Mendapatkan isi tabel
        $data = array_slice($rows, $headerRow + 1);

        // Menyimpan header dan isi tabel dalam variabel
        $headerVariable = $header;
        dd($headerVariable);
        $dataVariable = $data;


        // Lakukan operasi atau tindakan lain dengan headerVariable dan dataVariable
        // ...

        // Kembalikan respons atau lakukan redirect sesuai kebutuhan
        // ...



        $data = Data::when(auth()->user()->hasAnyRole('produsen'), fn($q) => $q->where('opd_id', auth()->user()->opd_id))->findOrFail($request->id_data);

        if ($data->status_id != Data::STATUS_SETUJU && $data->status_id != Data::STATUS_REVISI) {
            return response()->json(['message' => 'invalid'], 403);
        }

        $fileName = $request->file('berkas')->getClientOriginalName();
        $storedPath = $request->file('berkas')->storeAs('public/exports/' . Str::slug($data->nama_data), $fileName);

        if (!$storedPath) {
            return response([], 500);
        }

        $berkas = $data->berkas()->create([
            'visual_id' => $request->visual_id,
            'tahun' => $request->tahun,
            'name' => $fileName,
            'size' => Storage::size($storedPath),
            'path' => $storedPath
        ]);
        Alert::success('Berhasil', 'Data Berhasil Dihapus!');
        return redirect()->back();
    }
}
