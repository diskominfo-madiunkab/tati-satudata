<?php

namespace App\Http\Controllers;

use App\Models\Visualisasi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class VisualisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visualisasis = Visualisasi::orderBy('created_at', 'desc')->get();
        return view('pages.contents.administrator.indexvisualisasi', compact('visualisasis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.contents.administrator.createvisualisasi');
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
            'title'       => 'required|string|max:500',
            'tableau_url' => 'required|url',
            'content'     => 'nullable|string',
        ]);

        Visualisasi::create([
            'title'       => $request->title,
            'tableau_url' => $request->tableau_url,
            'content'     => $request->content ?: '',
        ]);

        Alert::success('Berhasil', 'Data Visualisasi Tableau Berhasil Ditambahkan!');
        return redirect()->route('visualisasi.index')->with('success', 'Visualisasi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $visualisasi = Visualisasi::findOrFail($id);
        return view('pages.contents.administrator.editvisualisasi', compact('visualisasi'));
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
            'title'       => 'required|string|max:500',
            'tableau_url' => 'required|url',
            'content'     => 'nullable|string',
        ]);

        $visualisasi = Visualisasi::findOrFail($id);
        $visualisasi->update([
            'title'       => $request->title,
            'tableau_url' => $request->tableau_url,
            'content'     => $request->content ?: '',
        ]);

        Alert::success('Berhasil', 'Data Visualisasi Tableau Berhasil Diperbarui!');
        return redirect()->route('visualisasi.index')->with('success', 'Visualisasi berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $visualisasi = Visualisasi::findOrFail($id);
        $visualisasi->delete();

        Alert::success('Berhasil', 'Data Visualisasi Berhasil Dihapus!');
        return redirect()->route('visualisasi.index');
    }
}

