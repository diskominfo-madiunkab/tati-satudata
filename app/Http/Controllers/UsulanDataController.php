<?php

namespace App\Http\Controllers;

use App\Models\UsulanData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

class UsulanDataController extends Controller
{
    public function index()
    {
        $data = UsulanData::all();

        return view('pages.contents.administrator.indexusulandata', compact('data'));
    }

    public function destroy($id)
    {
        try {
            // Dekripsi ID
            $id = Crypt::decrypt($id);

            // Temukan record berdasarkan ID
            $usulan = UsulanData::findOrFail($id);

            // Hapus record
            $usulan->delete();

            // Tampilkan alert sukses
            Alert::success('Berhasil', 'Data Berhasil Dihapus!');
        } catch (\Exception $e) {
            // Tampilkan alert gagal jika terjadi kesalahan
            Alert::error('Gagal', 'Data Gagal Dihapus! ' . $e->getMessage());
        }

        return redirect()->route('usulan-data.index');
    }
}
