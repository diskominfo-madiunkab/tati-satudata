<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class OpdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Opd::data_opd();
        return view('pages.contents.administrator.indexopd', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.contents.administrator.createopd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Opd::create([
            'nama_opd' => $request->nama_opd,
            'nip_penjabat' => $request->nip_penjabat,
            'nama_penjabat' => $request->nama_penjabat,
            'pangkat_penjabat' => $request->pangkat_penjabat,
            'jabatan_penjabat' => $request->jabatan_penjabat,
        ]);
        activity()->log('Menambahkan OPD');
        Alert::success('Berhasil', 'Berhasil Menambahkan Data OPD!');
        return redirect('/opd');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Opd  $opd
     * @return \Illuminate\Http\Response
     */
    public function show(Opd $opd)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Opd  $opd
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Opd::findOrFail($id);
        // dd($user);
        return view('pages.contents.administrator.editopd', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Opd  $opd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Opd::findOrFail($id);
        $user->update([
            'nama_opd' => $request->nama_opd,
            'nip_penjabat' => $request->nip_penjabat,
            'nama_penjabat' => $request->nama_penjabat,
            'pangkat_penjabat' => $request->pangkat_penjabat,
            'jabatan_penjabat' => $request->jabatan_penjabat,
        ]);
        activity()->log('Mengedit OPD');
        Alert::success('Berhasil', 'Berhasil Mengedit Data OPD!');
        return redirect('/opd');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Opd  $opd
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Opd::findOrFail($id);
        // dd($user);
        $user->delete();
        activity()->log('Menghapus OPD');
        Alert::success('Berhasil', 'Berhasil Menghapus Data OPD!');
        return redirect('/opd');
    }

    public function opds()
    {
        return Opd::select('id', 'nama_opd')->get();
    }
}
