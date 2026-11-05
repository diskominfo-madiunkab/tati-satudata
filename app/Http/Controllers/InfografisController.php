<?php

namespace App\Http\Controllers;

use App\Models\Infografis;
use App\Models\InfografisImage;
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
        $blogs = Infografis::with('images')->orderBy('created_at', 'desc')->get();
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
            'title'     => 'required|string|max:500',
            'content'   => 'required',
            'tableau'   => 'nullable|string',
            'image'     => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'images.*'  => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
        ]);

        $coverImageName = '';
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/blogs', $image->hashName());
            $coverImageName = $image->hashName();
        } elseif ($request->hasFile('images') && count($request->file('images')) > 0) {
            $firstImage = $request->file('images')[0];
            $coverImageName = $firstImage->hashName();
        }

        $blog = Infografis::create([
            'image'     => $coverImageName,
            'title'     => $request->title,
            'tableau'   => $request->tableau,
            'content'   => $request->content
        ]);

        // Upload multiple images for carousel slider (Instagram concept)
        if ($request->hasFile('images')) {
            $order = 1;
            foreach ($request->file('images') as $file) {
                $file->storeAs('public/blogs', $file->hashName());
                InfografisImage::create([
                    'infografis_id' => $blog->id,
                    'image'         => 'public/blogs/' . $file->hashName(),
                    'urutan'        => $order++,
                ]);
            }
        }

        Alert::success('Berhasil', 'Data Infografis Berhasil Ditambahkan!');
        return redirect()->route('infografis.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $infografis = Infografis::with('images')->findOrFail($id);
        return view('pages.contents.administrator.editinfografis', compact('infografis'));
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
        $this->validate($request, [
            'title'     => 'required|string|max:500',
            'tableau'   => 'nullable|string',
            'content'   => 'required',
            'image'     => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
            'images.*'  => 'nullable|image|mimes:png,jpg,jpeg|max:5120',
        ]);

        $blog = Infografis::findOrFail($id);

        if ($request->hasFile('image')) {
            if ($blog->image) {
                Storage::disk('local')->delete('public/blogs/' . $blog->image);
            }
            $image = $request->file('image');
            $image->storeAs('public/blogs', $image->hashName());
            $blog->image = $image->hashName();
        }

        $blog->title = $request->title;
        $blog->tableau = $request->tableau;
        $blog->content = $request->content;
        $blog->save();

        // Additional multi images upload
        if ($request->hasFile('images')) {
            $currentOrder = InfografisImage::where('infografis_id', $blog->id)->max('urutan') ?: 0;
            foreach ($request->file('images') as $file) {
                $currentOrder++;
                $file->storeAs('public/blogs', $file->hashName());
                InfografisImage::create([
                    'infografis_id' => $blog->id,
                    'image'         => 'public/blogs/' . $file->hashName(),
                    'urutan'        => $currentOrder,
                ]);
            }
        }

        Alert::success('Berhasil', 'Data Infografis Berhasil Diperbarui!');
        return redirect()->route('infografis.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Infografis::with('images')->findOrFail($id);

        if ($blog->image) {
            Storage::disk('local')->delete('public/blogs/' . $blog->image);
        }

        foreach ($blog->images as $subImg) {
            Storage::disk('local')->delete($subImg->image);
            $subImg->delete();
        }

        $blog->delete();

        Alert::success('Berhasil', 'Data Berhasil Dihapus!');
        return redirect()->route('infografis.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function destroyImage($id)
    {
        $image = InfografisImage::findOrFail($id);
        Storage::disk('local')->delete($image->image);
        $image->delete();

        Alert::success('Berhasil', 'Gambar Slide Berhasil Dihapus!');
        return redirect()->back();
    }
}
