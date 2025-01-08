<?php

namespace App\Http\Controllers;

use App\Models\MasterTahun;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MasterTahunController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = MasterTahun::all();
        return view('pages.contents.administrator.indextahun', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.contents.administrator.createtahun');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        MasterTahun::create([
            'tahun' => $request->tahun,

        ]);
        activity()->log('Menambahkan Tahun');
        Alert::success('Berhasil', 'Data Berhasil Ditambah!');
        return redirect('/tahun');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MasterTahun  $masterTahun
     * @return \Illuminate\Http\Response
     */
    public function show(MasterTahun $masterTahun)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MasterTahun  $masterTahun
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = MasterTahun::findOrFail($id);
        // dd($user);
        return view('pages.contents.administrator.edittahun', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MasterTahun  $masterTahun
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = MasterTahun::findOrFail($id);
        $user->update([
            'tahun' => $request->tahun,

        ]);
        activity()->log('Mengedit Sumber Referensi');
        Alert::success('Berhasil', 'Data Berhasil Diubah!');
        return redirect('/tahun');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MasterTahun  $masterTahun
     * @return \Illuminate\Http\Response
     */
    public function destroy(MasterTahun $masterTahun)
    {
        //
    }

    public function aktifkan($id)
    {
        $data = MasterTahun::findOrFail($id);
        if ($data->is_active == 1) {
            $data->update([
                'is_active' => 0,

            ]);
        } elseif (($data->is_active == 0)) {
            $data->update([
                'is_active' => 1,

            ]);
        }
        return redirect('/tahun');
    }
}
