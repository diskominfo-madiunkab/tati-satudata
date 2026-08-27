<?php

namespace App\Http\Controllers;

use App\Models\Visualisasi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class VisualisasiController extends Controller
{
    public function index()
    {
        $visualisasis = Visualisasi::orderBy('created_at', 'desc')->get();
        return view('pages.contents.administrator.visualisasi.index', compact('visualisasis'));
    }

    public function create()
    {
        return view('pages.contents.administrator.visualisasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'tableau_url' => 'required|url',
            'content' => 'nullable|string',
        ]);

        Visualisasi::create([
            'title' => $request->title,
            'tableau_url' => $request->tableau_url,
            'content' => $request->content,
        ]);

        Alert::success('Berhasil', 'Visualisasi Tableau berhasil ditambahkan!');
        return redirect()->route('kelola-visualisasi.index')->with('success', 'Visualisasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $visualisasi = Visualisasi::findOrFail($id);
        return view('pages.contents.administrator.visualisasi.edit', compact('visualisasi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'tableau_url' => 'required|url',
            'content' => 'nullable|string',
        ]);

        $visualisasi = Visualisasi::findOrFail($id);
        $visualisasi->update([
            'title' => $request->title,
            'tableau_url' => $request->tableau_url,
            'content' => $request->content,
        ]);

        Alert::success('Berhasil', 'Visualisasi Tableau berhasil diperbarui!');
        return redirect()->route('kelola-visualisasi.index')->with('success', 'Visualisasi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $visualisasi = Visualisasi::findOrFail($id);
        $visualisasi->delete();

        Alert::success('Berhasil', 'Visualisasi Tableau berhasil dihapus!');
        return redirect()->route('kelola-visualisasi.index')->with('success', 'Visualisasi berhasil dihapus');
    }
}
