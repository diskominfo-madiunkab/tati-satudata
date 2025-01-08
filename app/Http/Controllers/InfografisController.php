<?php

namespace App\Http\Controllers;

use App\Models\Infografis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class InfografisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Infografis::orderBy('created_at', 'desc')->get();
        return view('pages.contents.administrator.indexinfografis', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.contents.administrator.createinfografis');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image'     => 'required|image|mimes:png,jpg,jpeg',
            'title'     => 'required',
            'content'   => 'required'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/blogs', $image->hashName());

        $blog = Infografis::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content
        ]);

        if ($blog) {
            //redirect dengan pesan sukses
            Alert::success('Berhasil', 'Data Berhasil Ditambah!');
            return redirect()->route('infografis.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('infografis.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Infografis  $infografis
     * @return \Illuminate\Http\Response
     */
    public function show(Infografis $infografis)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Infografis  $infografis
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $infografis = Infografis::findOrFail($id);
        return view('pages.contents.administrator.editinfografis', compact('infografis'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Infografis  $infografis
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $this->validate($request, [
            'title'     => 'required',
            'content'   => 'required'
        ]);

        //get data Blog by ID
        $blog = Infografis::findOrFail($id);

        if ($request->file('image') == "") {

            $blog->update([
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        } else {

            //hapus old image
            Storage::disk('local')->delete('public/blogs/' . $blog->image);

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/blogs', $image->hashName());

            $blog->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content
            ]);
        }

        if ($blog) {
            //redirect dengan pesan sukses
            Alert::success('Berhasil', 'Data Berhasil Diubah!');
            return redirect()->route('infografis.index')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('infografis.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Infografis  $infografis
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Infografis::findOrFail($id);
        Storage::disk('local')->delete('public/blogs/' . $blog->image);
        $blog->delete();

        if ($blog) {
            //redirect dengan pesan sukses
            Alert::success('Berhasil', 'Data Berhasil Dihapus!');
            return redirect()->route('infografis.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('infografis.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
