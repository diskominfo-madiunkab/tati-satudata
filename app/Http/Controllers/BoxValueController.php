<?php

namespace App\Http\Controllers;

use App\Models\BoxValue;
use App\Models\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

class BoxValueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = BoxValue::with('data')->get();
        return view('pages.contents.walidata.box_value.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Data::whereIn('status_id', [Data::STATUS_TERPUBLIKASI])
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan', 'publikasi'])
            ->orderBy('tahun', 'DESC')
            ->get();
        return view('pages.contents.walidata.box_value.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'logo'     => 'required',
            'judul'     => 'required',
            'ringkasan_nilai'   => 'required',
            'satuan'   => 'required',
            'data_id'   => 'required',
        ]);
        // $ringkasanNilai = str_replace(',', '.', $request->input('ringkasan_nilai'));
        // dd($ringkasanNilai);
        $box = BoxValue::create([
            'logo'     => $request->logo,
            'judul'     => $request->judul,
            'ringkasan_nilai'   => $request->ringkasan_nilai,
            'satuan'     => $request->satuan,
            'data_id'     => $request->data_id,
        ]);

        if ($box) {
            Alert::success('Berhasil', 'Data Berhasil Ditambah!');
        } else {
            Alert::error('Gagal', 'Data Gagal Ditambah!');
        }
        return redirect()->route('box-value.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BoxValue  $boxValue
     * @return \Illuminate\Http\Response
     */
    public function show(BoxValue $boxValue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BoxValue  $boxValue
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id);
        $id = Crypt::decrypt($id);
        $data = BoxValue::findOrFail($id);
        $dt =
            Data::whereIn('status_id', [Data::STATUS_TERPUBLIKASI])
            ->with(['opd', 'status', 'berkas', 'indikator', 'variabel', 'standar', 'kegiatan', 'publikasi'])
            ->orderBy('tahun', 'DESC')
            ->get();
        return view('pages.contents.walidata.box_value.edit', compact('data', 'dt'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BoxValue  $boxValue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'logo'     => 'required',
            'judul'     => 'required',
            'ringkasan_nilai'   => 'required',
            'satuan'   => 'required',
            'data_id'   => 'required',
        ]);
        $boxvalue = BoxValue::findOrFail($id);
        $boxvalue->logo     = $request->logo;
        $boxvalue->judul     = $request->judul;
        $boxvalue->ringkasan_nilai   = $request->ringkasan_nilai;
        $boxvalue->satuan     = $request->satuan;
        $boxvalue->data_id     = $request->data_id;
        $boxvalue->save();
        Alert::success('Berhasil', 'Data Berhasil Diperbarui!');
        return redirect()->route('box-value.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BoxValue  $boxValue
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Dekripsi ID
            $id = Crypt::decrypt($id);

            // Temukan record berdasarkan ID
            $boxvalue = BoxValue::findOrFail($id);

            // Hapus record
            $boxvalue->delete();

            // Tampilkan alert sukses
            Alert::success('Berhasil', 'Data Berhasil Dihapus!');
        } catch (\Exception $e) {
            // Tampilkan alert gagal jika terjadi kesalahan
            Alert::error('Gagal', 'Data Gagal Dihapus! ' . $e->getMessage());
        }

        return redirect()->route('box-value.index');
    }
}
