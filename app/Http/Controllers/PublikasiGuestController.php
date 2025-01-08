<?php

namespace App\Http\Controllers;

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
        $publikasi = PublikasiGuest::orderBy('created_at', 'desc')->get();
        return view('pages.contents.administrator.indexpublikasi', compact('publikasi'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.contents.administrator.createpublikasi');
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
            'image'     => 'required|image|mimes:png,jpg,jpeg',
            'title'     => 'required',
            'content'   => 'required',
            'pdf' => 'required|mimes:pdf|max:13177512',
        ]);

        $pdf = $request->file('pdf');
        $pdfSize = $pdf->getSize();
        $maxSize = 13177512;
        // dd($pdfSize);
        if ($pdfSize > $maxSize) {
            // File terlalu besar, berikan pesan kesalahan
            // Alert::error('Gagal', 'Ukuran file PDF tidak boleh melebihi 10MB.!');
            return redirect()->back()->withErrors(['pdf' => 'Ukuran file PDF tidak boleh melebihi 10MB.']);
        }
        $nama_file = date('Ymd') . '-' . $pdf->getClientOriginalName();
        $path = $pdf->storeAs('public/publications', $nama_file);
        //upload image
        $image = $request->file('image');
        $image->storeAs('public/blogs', $image->hashName());

        $publication = PublikasiGuest::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content,
            'pdf_path' => $path,
        ]);

        Alert::success('Berhasil', 'Data Berhasil Ditambah!');
        return redirect()->route('publikasi-guest.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PublikasiGuest  $publikasiGuest
     * @return \Illuminate\Http\Response
     */
    public function show(PublikasiGuest $publikasiGuest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PublikasiGuest  $publikasiGuest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $publikasi = PublikasiGuest::findOrFail($id);
        return view('pages.contents.administrator.editpublikasi', compact('publikasi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PublikasiGuest  $publikasiGuest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'image'     => 'image|mimes:png,jpg,jpeg',
            'title'     => 'required',
            'content'   => 'required',
            'pdf' => 'mimes:pdf|max:13177512',
        ]);

        //get data Blog by ID
        $publikasi = PublikasiGuest::findOrFail($id);

        if ($request->hasFile('image') || $request->hasFile('pdf')) {
            // Validation rules for image and PDF
            $validationRules = [
                'image' => 'image|mimes:png,jpg,jpeg',
                'pdf'   => 'mimes:pdf|max:13177512',
            ];

            $request->validate($validationRules);

            if ($request->hasFile('image')) {
                // Delete old image
                Storage::disk('local')->delete('public/blogs/' . $publikasi->image);

                // Upload new image
                $image = $request->file('image');
                $image->storeAs('public/blogs', $image->hashName());

                $publikasi->image = $image->hashName();
            }

            if ($request->hasFile('pdf')) {
                // Delete old PDF
                Storage::disk('local')->delete('public/publications/' . $publikasi->pdf_path);

                // Validate PDF size
                $pdfSize = $request->file('pdf')->getSize();
                $maxPdfSize = 13177512;

                if ($pdfSize > $maxPdfSize) {
                    return redirect()->route('publikasi-guest.index')->withErrors(['pdf' => 'Ukuran file PDF tidak boleh melebihi 10MB.']);
                }

                // Upload new PDF
                $pdf = $request->file('pdf');
                $nama_file = date('Ymd') . '-' . $pdf->getClientOriginalName();
                $path = $pdf->storeAs('public/publications', $nama_file);

                $publikasi->pdf_path = $path;
            }
        }

        // Update title and content
        $publikasi->title = $request->title;
        $publikasi->content = $request->content;
        $publikasi->save();
        Alert::success('Berhasil', 'Data Berhasil Diperbarui!');
        return redirect()->route('publikasi-guest.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PublikasiGuest  $publikasiGuest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PublikasiGuest::findOrFail($id)->delete();
        Alert::success('Berhasil', 'Data Berhasil Dihapus!');
        return redirect()->route('publikasi-guest.index');
    }

    public function download($id)
    {
        // dd('sss');
        $publication = PublikasiGuest::findOrFail($id);
        // dd($publication->pdf_path);
        if (Storage::exists($publication->pdf_path)) {
            return Storage::download($publication->pdf_path);
        }

        abort(404, 'File tidak tersedia pada server');
    }
}
