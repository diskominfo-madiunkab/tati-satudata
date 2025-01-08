<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Laravolt\Indonesia\Models\City;
use RealRashid\SweetAlert\Facades\Alert;

class WilayahController extends Controller
{
    public function province()
    {
        return \Indonesia::allProvinces();
    }

    public function city($provinceId = null)
    {
        return [
            'cities' => City::when(!empty($provinceId), fn ($q) => $q->where('province_code', $provinceId))->get()
        ];
    }

    public function index()
    {
        $data = Wilayah::all();
        return view('pages.contents.administrator.kelolademografis.index', compact('data'));
    }

    public function create()
    {
        return view('pages.contents.administrator.kelolademografis.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'icon'     => 'required',
            'narasi_data'     => 'required',
            'jml_data'   => 'required',
        ]);

        $publication = Wilayah::create([
            'icon'     => $request->icon,
            'narasi_data'     => $request->narasi_data,
            'jml_data'   => $request->jml_data,
            'narasi_1'     => $request->narasi_1,
            'jml_narasi_1'     => $request->jml_narasi_1,
            'narasi_2'   => $request->narasi_2,
            'jml_narasi_2'   => $request->jml_narasi_2,
        ]);

        if ($publication) {
            Alert::success('Berhasil', 'Data Berhasil Ditambah!');
        } else {
            Alert::error('Gagal', 'Data Gagal Ditambah!');
        }
        return redirect()->route('data-demografis.index');
    }

    public function edit($id)
    {
        $id = decrypt($id);
        $data = Wilayah::findOrFail($id);
        return view('pages.contents.administrator.kelolademografis.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'icon'     => 'required',
            'narasi_data'     => 'required',
            'jml_data'   => 'required',
        ]);
        $demografis = Wilayah::findOrFail($id);
        $demografis->icon     = $request->icon;
        $demografis->narasi_data     = $request->narasi_data;
        $demografis->jml_data   = $request->jml_data;
        $demografis->narasi_1     = $request->narasi_1;
        $demografis->jml_narasi_1     = $request->jml_narasi_1;
        $demografis->narasi_2   = $request->narasi_2;
        $demografis->jml_narasi_2   = $request->jml_narasi_2;
        $demografis->save();
        Alert::success('Berhasil', 'Data Berhasil Diperbarui!');
        return redirect()->route('data-demografis.index');
    }

    public function destroy($id)
    {
        try {
            // Dekripsi ID
            $id = Crypt::decrypt($id);

            // Temukan record berdasarkan ID
            $dataDemografi = Wilayah::findOrFail($id);

            // Hapus record
            $dataDemografi->delete();

            // Tampilkan alert sukses
            Alert::success('Berhasil', 'Data Berhasil Dihapus!');
        } catch (\Exception $e) {
            // Tampilkan alert gagal jika terjadi kesalahan
            Alert::error('Gagal', 'Data Gagal Dihapus! ' . $e->getMessage());
        }

        return redirect()->route('data-demografis.index');
    }
}
