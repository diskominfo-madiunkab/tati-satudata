<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use App\Models\PublikasiGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class PublikasiGuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $publikasi = PublikasiGuest::with('opd')->orderBy('created_at', 'desc')->get();
        return view('pages.contents.administrator.indexpublikasi', compact('publikasi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $opds = Opd::orderBy('nama_opd', 'asc')->get();
        return view('pages.contents.administrator.createpublikasi', compact('opds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $isTerbit = $request->input('status') === 'Terbit';

        $rules = [
            'title'                 => 'required|string|max:500',
            'status'                => 'required|in:Terbit,Belum Terbit',
            'opd_id'                => 'nullable|exists:opds,id',
            'instansi'              => 'nullable|string|max:255',
            'tahun'                 => 'nullable|string|max:20',
            'jadwal_rencana_terbit' => 'nullable|string|max:255',
            'jadwal_terbit'         => 'nullable|string|max:255',
            'frekuensi'             => 'nullable|string|max:50',
            'content'               => 'nullable|string',
        ];

        if ($isTerbit) {
            $rules['image'] = 'required|image|mimes:png,jpg,jpeg|max:5120';
            $rules['pdf']   = 'required|mimes:pdf|max:13177512';
        } else {
            $rules['image'] = 'nullable|image|mimes:png,jpg,jpeg|max:5120';
            $rules['pdf']   = 'nullable|mimes:pdf|max:13177512';
        }

        $request->validate($rules);

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/blogs', $image->hashName());
            $imageName = $image->hashName();
        }

        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $pdf = $request->file('pdf');
            $nama_file = date('Ymd') . '-' . $pdf->getClientOriginalName();
            $pdfPath = $pdf->storeAs('public/publications', $nama_file);
        }

        $instansiName = $request->instansi;
        if ($request->filled('opd_id')) {
            $opd = Opd::find($request->opd_id);
            if ($opd) {
                $instansiName = $opd->nama_opd;
            }
        }

        PublikasiGuest::create([
            'title'                 => $request->title,
            'status'                => $request->status,
            'opd_id'                => $request->opd_id,
            'instansi'              => $instansiName,
            'tahun'                 => $request->tahun,
            'jadwal_rencana_terbit' => $request->jadwal_rencana_terbit,
            'jadwal_terbit'         => $request->jadwal_terbit,
            'frekuensi'             => $request->frekuensi ?: 'Tahunan',
            'content'               => $request->content ?: '',
            'image'                 => $imageName ?: '',
            'pdf_path'              => $pdfPath ?: '',
        ]);

        Alert::success('Berhasil', 'Data Buku Publikasi Berhasil Ditambahkan!');
        return redirect()->route('publikasi-guest.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $publikasi = PublikasiGuest::findOrFail($id);
        $opds = Opd::orderBy('nama_opd', 'asc')->get();
        return view('pages.contents.administrator.editpublikasi', compact('publikasi', 'opds'));
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
        $publikasi = PublikasiGuest::findOrFail($id);
        $isTerbit = $request->input('status') === 'Terbit';

        $rules = [
            'title'                 => 'required|string|max:500',
            'status'                => 'required|in:Terbit,Belum Terbit',
            'opd_id'                => 'nullable|exists:opds,id',
            'instansi'              => 'nullable|string|max:255',
            'tahun'                 => 'nullable|string|max:20',
            'jadwal_rencana_terbit' => 'nullable|string|max:255',
            'jadwal_terbit'         => 'nullable|string|max:255',
            'frekuensi'             => 'nullable|string|max:50',
            'content'               => 'nullable|string',
        ];

        if ($isTerbit && empty($publikasi->image)) {
            $rules['image'] = 'required|image|mimes:png,jpg,jpeg|max:5120';
        } else {
            $rules['image'] = 'nullable|image|mimes:png,jpg,jpeg|max:5120';
        }

        if ($isTerbit && empty($publikasi->pdf_path)) {
            $rules['pdf'] = 'required|mimes:pdf|max:13177512';
        } else {
            $rules['pdf'] = 'nullable|mimes:pdf|max:13177512';
        }

        $request->validate($rules);

        if ($request->hasFile('image')) {
            if ($publikasi->image) {
                Storage::disk('local')->delete('public/blogs/' . $publikasi->image);
            }
            $image = $request->file('image');
            $image->storeAs('public/blogs', $image->hashName());
            $publikasi->image = $image->hashName();
        }

        if ($request->hasFile('pdf')) {
            if ($publikasi->pdf_path) {
                Storage::disk('local')->delete($publikasi->pdf_path);
            }
            $pdf = $request->file('pdf');
            $nama_file = date('Ymd') . '-' . $pdf->getClientOriginalName();
            $path = $pdf->storeAs('public/publications', $nama_file);
            $publikasi->pdf_path = $path;
        }

        $instansiName = $request->instansi;
        if ($request->filled('opd_id')) {
            $opd = Opd::find($request->opd_id);
            if ($opd) {
                $instansiName = $opd->nama_opd;
            }
        }

        $publikasi->title = $request->title;
        $publikasi->status = $request->status;
        $publikasi->opd_id = $request->opd_id;
        $publikasi->instansi = $instansiName;
        $publikasi->tahun = $request->tahun;
        $publikasi->jadwal_rencana_terbit = $request->jadwal_rencana_terbit;
        $publikasi->jadwal_terbit = $request->jadwal_terbit;
        $publikasi->frekuensi = $request->frekuensi ?: ($publikasi->frekuensi ?: 'Tahunan');
        $publikasi->content = $request->content ?: '';
        $publikasi->save();

        Alert::success('Berhasil', 'Data Buku Publikasi Berhasil Diperbarui!');
        return redirect()->route('publikasi-guest.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $publikasi = PublikasiGuest::findOrFail($id);
        if ($publikasi->image) {
            Storage::disk('local')->delete('public/blogs/' . $publikasi->image);
        }
        if ($publikasi->pdf_path) {
            Storage::disk('local')->delete($publikasi->pdf_path);
        }
        $publikasi->delete();

        Alert::success('Berhasil', 'Data Berhasil Dihapus!');
        return redirect()->route('publikasi-guest.index');
    }

    public function download($id)
    {
        $publication = PublikasiGuest::findOrFail($id);
        if ($publication->pdf_path && Storage::exists($publication->pdf_path)) {
            return Storage::download($publication->pdf_path);
        }

        abort(404, 'File PDF tidak tersedia pada server');
    }
}
