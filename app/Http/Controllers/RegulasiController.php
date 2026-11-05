<?php

namespace App\Http\Controllers;

use App\Models\Regulasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class RegulasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regulasis = Regulasi::orderBy('tahun', 'desc')->orderBy('created_at', 'desc')->get();
        return view('pages.contents.administrator.indexregulasi', compact('regulasis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.contents.administrator.createregulasi');
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
            'judul'        => 'required|string|max:500',
            'nomor'        => 'nullable|string|max:255',
            'tahun'        => 'nullable|string|max:10',
            'kategori'     => 'required|string|max:100',
            'status'       => 'required|in:Berlaku,Tidak Berlaku',
            'tentang'      => 'nullable|string',
            'file_dokumen' => 'nullable|mimes:pdf|max:15360',
        ]);

        $filePath = null;
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $fileName = date('Ymd_His') . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/regulasi', $fileName);
        }

        Regulasi::create([
            'judul'        => $request->judul,
            'nomor'        => $request->nomor,
            'tahun'        => $request->tahun,
            'kategori'     => $request->kategori,
            'status'       => $request->status,
            'tentang'      => $request->tentang ?: '',
            'file_dokumen' => $filePath ?: '',
        ]);

        Alert::success('Berhasil', 'Data Regulasi Berhasil Ditambahkan!');
        return redirect()->route('regulasi.index')->with('success', 'Regulasi berhasil disimpan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $regulasi = Regulasi::findOrFail($id);
        return view('pages.contents.administrator.editregulasi', compact('regulasi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul'        => 'required|string|max:500',
            'nomor'        => 'nullable|string|max:255',
            'tahun'        => 'nullable|string|max:10',
            'kategori'     => 'required|string|max:100',
            'status'       => 'required|in:Berlaku,Tidak Berlaku',
            'tentang'      => 'nullable|string',
            'file_dokumen' => 'nullable|mimes:pdf|max:15360',
        ]);

        $regulasi = Regulasi::findOrFail($id);

        if ($request->hasFile('file_dokumen')) {
            if ($regulasi->file_dokumen) {
                Storage::delete($regulasi->file_dokumen);
            }
            $file = $request->file('file_dokumen');
            $fileName = date('Ymd_His') . '_' . $file->getClientOriginalName();
            $regulasi->file_dokumen = $file->storeAs('public/regulasi', $fileName);
        }

        $regulasi->judul = $request->judul;
        $regulasi->nomor = $request->nomor;
        $regulasi->tahun = $request->tahun;
        $regulasi->kategori = $request->kategori;
        $regulasi->status = $request->status;
        $regulasi->tentang = $request->tentang ?: '';
        $regulasi->save();

        Alert::success('Berhasil', 'Data Regulasi Berhasil Diperbarui!');
        return redirect()->route('regulasi.index')->with('success', 'Regulasi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $regulasi = Regulasi::findOrFail($id);
        if ($regulasi->file_dokumen) {
            Storage::delete($regulasi->file_dokumen);
        }
        $regulasi->delete();

        Alert::success('Berhasil', 'Data Regulasi Berhasil Dihapus!');
        return redirect()->route('regulasi.index');
    }
}

