<?php

namespace App\Http\Controllers;

use App\Models\StandarData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class StandartDataController extends Controller
{
    /**
     * Display Master Standar Data for Produsen (Read-Only reference)
     */
    public function index(Request $request)
    {
        $query = StandarData::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('konsep', 'like', "%{$search}%")
                  ->orWhere('definisi', 'like', "%{$search}%")
                  ->orWhere('ukuran', 'like', "%{$search}%")
                  ->orWhere('satuan', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('klasifikasi', 'like', "%{$search}%");
            });
        }

        $totalStandar = StandarData::count();
        $totalDenganKode = StandarData::whereNotNull('kode')->where('kode', '!=', '')->count();
        $standarData = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        return view('pages.contents.produsen.standart_data.index', compact('standarData', 'totalStandar', 'totalDenganKode'));
    }

    /**
     * Display Master Standar Data for Walidata (Full Management)
     */
    public function index_walidata(Request $request)
    {
        $query = StandarData::query();

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('konsep', 'like', "%{$search}%")
                  ->orWhere('definisi', 'like', "%{$search}%")
                  ->orWhere('ukuran', 'like', "%{$search}%")
                  ->orWhere('satuan', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('klasifikasi', 'like', "%{$search}%");
            });
        }

        $totalStandar = StandarData::count();
        $totalDenganKode = StandarData::whereNotNull('kode')->where('kode', '!=', '')->count();
        $standarData = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        return view('pages.contents.walidata.standart_data.index', compact('standarData', 'totalStandar', 'totalDenganKode'));
    }

    /**
     * Store a new Master Standar Data (Walidata)
     */
    public function storeMaster(Request $request)
    {
        $request->validate([
            'konsep' => 'required|string|max:255',
            'definisi' => 'nullable|string',
            'ukuran' => 'nullable|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'klasifikasi' => 'nullable|string|max:255',
            'kode' => 'nullable|string|max:100',
        ]);

        StandarData::create([
            'konsep' => $request->konsep,
            'definisi' => $request->definisi,
            'ukuran' => $request->ukuran,
            'satuan' => $request->satuan,
            'klasifikasi' => $request->klasifikasi ?? 'Tidak ada',
            'kode' => $request->kode,
        ]);

        Alert::success('Berhasil', 'Standar Data berhasil ditambahkan ke database master!');
        return redirect()->route('walidata.standar-data.index');
    }

    /**
     * Update an existing Master Standar Data (Walidata)
     */
    public function updateMaster(Request $request, $id)
    {
        $request->validate([
            'konsep' => 'required|string|max:255',
            'definisi' => 'nullable|string',
            'ukuran' => 'nullable|string|max:255',
            'satuan' => 'nullable|string|max:255',
            'klasifikasi' => 'nullable|string|max:255',
            'kode' => 'nullable|string|max:100',
        ]);

        $item = StandarData::findOrFail($id);
        $item->update([
            'konsep' => $request->konsep,
            'definisi' => $request->definisi,
            'ukuran' => $request->ukuran,
            'satuan' => $request->satuan,
            'klasifikasi' => $request->klasifikasi ?? 'Tidak ada',
            'kode' => $request->kode,
        ]);

        Alert::success('Berhasil', 'Standar Data berhasil diperbarui!');
        return redirect()->route('walidata.standar-data.index');
    }

    /**
     * Delete a Master Standar Data (Walidata)
     */
    public function destroyMaster($id)
    {
        $item = StandarData::findOrFail($id);
        $item->delete();

        Alert::success('Berhasil', 'Standar Data berhasil dihapus!');
        return redirect()->route('walidata.standar-data.index');
    }

    /* Legacy routes redirect to master index */
    public function revisi() { return redirect()->route('produsen.standar-data.index'); }
    public function setuju() { return redirect()->route('produsen.standar-data.index'); }
    public function revisi_walidata() { return redirect()->route('walidata.standar-data.index'); }
    public function setuju_walidata() { return redirect()->route('walidata.standar-data.index'); }
    public function verifikasiStandarData($id) { return redirect()->route('walidata.standar-data.index'); }
    public function verify(Request $request, $id) { return redirect()->route('walidata.standar-data.index'); }
    public function getKomentar($id) { return response()->json([]); }
    public function komentar(Request $request, $id) { return response()->json(['status' => 'success']); }
}
