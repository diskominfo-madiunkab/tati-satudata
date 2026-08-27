<?php

namespace App\Http\Controllers;

use App\Models\Regulasi;
use App\Models\MasterTahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class RegulasiController extends Controller
{
    public function index()
    {
        $regulasis = Regulasi::orderBy('tahun', 'desc')->orderBy('created_at', 'desc')->get();
        return view('pages.contents.administrator.regulasi.index', compact('regulasis'));
    }

    public function create()
    {
        $tahuns = MasterTahun::orderBy('tahun', 'desc')->get();
        return view('pages.contents.administrator.regulasi.create', compact('tahuns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'nomor' => 'nullable|string|max:100',
            'kategori' => 'required|string|max:100',
            'tahun' => 'required|numeric',
            'tentang' => 'nullable|string',
            'file_dokumen' => 'nullable|mimes:pdf|max:15000',
        ]);

        $filePath = null;
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $fileName = 'regulasi_' . time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/regulasi', $fileName);
            $filePath = str_replace('public/', '', $filePath);
        }

        Regulasi::create([
            'judul' => $request->judul,
            'nomor' => $request->nomor,
            'kategori' => $request->kategori,
            'tahun' => $request->tahun,
            'tentang' => $request->tentang,
            'status' => $request->status ?: 'Berlaku',
            'file_dokumen' => $filePath,
        ]);

        Alert::success('Berhasil', 'Regulasi berhasil ditambahkan!');
        return redirect()->route('kelola-regulasi.index')->with('success', 'Regulasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $regulasi = Regulasi::findOrFail($id);
        $tahuns = MasterTahun::orderBy('tahun', 'desc')->get();
        return view('pages.contents.administrator.regulasi.edit', compact('regulasi', 'tahuns'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'nomor' => 'nullable|string|max:100',
            'kategori' => 'required|string|max:100',
            'tahun' => 'required|numeric',
            'tentang' => 'nullable|string',
            'file_dokumen' => 'nullable|mimes:pdf|max:15000',
        ]);

        $regulasi = Regulasi::findOrFail($id);
        $filePath = $regulasi->file_dokumen;

        if ($request->hasFile('file_dokumen')) {
            if ($filePath && Storage::exists('public/' . $filePath)) {
                Storage::delete('public/' . $filePath);
            }
            $file = $request->file('file_dokumen');
            $fileName = 'regulasi_' . time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/regulasi', $fileName);
            $filePath = str_replace('public/', '', $filePath);
        }

        $regulasi->update([
            'judul' => $request->judul,
            'nomor' => $request->nomor,
            'kategori' => $request->kategori,
            'tahun' => $request->tahun,
            'tentang' => $request->tentang,
            'status' => $request->status ?: 'Berlaku',
            'file_dokumen' => $filePath,
        ]);

        Alert::success('Berhasil', 'Regulasi berhasil diperbarui!');
        return redirect()->route('kelola-regulasi.index')->with('success', 'Regulasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $regulasi = Regulasi::findOrFail($id);
        if ($regulasi->file_dokumen && Storage::exists('public/' . $regulasi->file_dokumen)) {
            Storage::delete('public/' . $regulasi->file_dokumen);
        }
        $regulasi->delete();

        Alert::success('Berhasil', 'Regulasi berhasil dihapus!');
        return redirect()->route('kelola-regulasi.index')->with('success', 'Regulasi berhasil dihapus');
    }
}
