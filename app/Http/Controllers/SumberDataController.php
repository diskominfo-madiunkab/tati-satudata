<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\ActivityLogger;
use App\Models\SumberData;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SumberDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = SumberData::all();
        return view('pages.contents.administrator.indexsumberdata', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.contents.administrator.createsumberdata');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        SumberData::create([
            'sumber_data' => $request->sumber_data,

        ]);
        activity()->log('Menambahkan Sumber Referensi');
        Alert::success('Berhasil', 'Berhasil Menambah Data!');
        return redirect('/sumberdata');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SumberData  $sumberData
     * @return \Illuminate\Http\Response
     */
    public function show(SumberData $sumberData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SumberData  $sumberData
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SumberData::findOrFail($id);
        // dd($user);
        return view('pages.contents.administrator.editsumberdata', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SumberData  $sumberData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = SumberData::findOrFail($id);
        $user->update([
            'sumber_data' => $request->sumber_data,

        ]);
        activity()->log('Mengedit Sumber Referensi');
        Alert::success('Berhasil', 'Berhasil Mengedit Data!');
        return redirect('/sumberdata');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SumberData  $sumberData
     * @return \Illuminate\Http\Response
     */
    public function destroy(SumberData $sumberData)
    {
        //
    }

    public function aktifkan($id)
    {
        $data = SumberData::findOrFail($id);
        if ($data->is_active == 1) {
            $data->update([
                'is_active' => 0,

            ]);
            Alert::success('Berhasil', 'Berhasil Non-aktifkan Data!');
        } elseif (($data->is_active == 0)) {
            $data->update([
                'is_active' => 1,

            ]);
            Alert::success('Berhasil', 'Berhasil Mengaktifkan Data!');
        }
        return redirect('/sumberdata');
    }
}
