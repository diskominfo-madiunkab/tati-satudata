<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class UpdownloadController extends Controller
{
    public function index()
    {
        $document = Document::get();
        return view('pages.contents.administrator.download', compact('document'));
    }

    public function proses_upload(Request $request)
    {
        $this->validate($request, [
            'document' => 'required|mimes:xlsx,xls',
            'keterangan' => 'required',
            'type' => 'required',
        ]);

        $upload = $request->file('document');
        $nama_file = date('Ymd') . '-' . $upload->getClientOriginalName();
        $path = $upload->storeAs('public/templates', $nama_file);

        Document::create([
            'document' => $nama_file,
            'path' => $path,
            'keterangan' => $request->keterangan,
            'type' => $request->type,
        ]);

        return redirect('/upload-download');
    }

    public function destroy($id)
    {
        Document::findOrFail($id)->delete();
        return redirect('/upload-download');
    }

    public function download($id)
    {
        $doc = Document::where('id', '=', $id)->firstOrFail();

        if (Storage::exists($doc->path)) {
            $downloadName = $doc->keterangan . '.' . pathinfo($doc->path, PATHINFO_EXTENSION);

            return Storage::download($doc->path, $downloadName);
        }

        abort(404, 'File tidak tersedia pada server');
    }

    public function download_template($id)
    {
        $doc = Document::where('id', '=', $id)->firstOrFail();
        // dd($doc);
        if (Storage::exists($doc->path)) {
            $downloadName = $doc->keterangan . '.' . pathinfo($doc->path, PATHINFO_EXTENSION);

            return Storage::download($doc->path, $downloadName);
        }

        abort(404, 'File tidak tersedia pada server');
    }
}
