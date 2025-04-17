<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Services\CkanApi\Facades\CkanApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        set_time_limit(600);
        $data = CkanApi::group()->all(['limit' => 100]);
        $result = $data['result'];
        // $data = CkanApi::dataset()->all();
        // dd($data);
        return view('pages.contents.administrator.kelolagroup.index', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.contents.administrator.kelolagroup.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        set_time_limit(600);
        $this->validate($request, [
            'image_url'     => 'required|image|mimes:png,jpg,jpeg',
            'name_group'     => 'required',
            'deskripsi'   => 'required'
        ]);

        //upload image
        $image = $request->file('image_url');
        $path = $image->storeAs('public/groups', $image->hashName());
        $public_path = Storage::url($path);
        // convert slug
        $slug = Str::slug($request->input('name_group'));
        // timestamp
        $currentTimestamp = Carbon::now()->format('Y-m-d\TH:i:s.u');

        $dataset = CkanApi::group()->create([
            'display_name' => $request->name_group,
            'description' => $request->deskripsi,
            'image_display_url' => $public_path,
            'package_count' => 0,
            'created' => $currentTimestamp,
            'name' => $slug,
            'is_organization' => false,
            'state' => "active",
            'image_url' => $public_path,
            'type' => "group",
            'title' => $request->name_group,
            'num_followers' => 0,
            'approval_status' => "approved",
        ]);

        // if (!$dataset || empty($dataset['result']) || (isset($dataset['success']) && !$dataset['success'])) {
        //     Log::error('Gagal publikasi data: ' . json_encode($dataset), ['Publikasi']);
        //     throw new \Exception('Error tidak diketahui');
        // }

        if ($dataset) {
            //redirect dengan pesan sukses
            Alert::success('Berhasil', 'Data Berhasil Ditambah!');
            return redirect()->route('group.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('group.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        set_time_limit(600);
        $find =  CkanApi::group()->show($id);
        $data = $find['result'];
        // dd($data);
        return view('pages.contents.administrator.kelolagroup.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        set_time_limit(600);
        $this->validate($request, [
            'image_url'     => 'image|mimes:png,jpg,jpeg',
            'name_group'     => 'required',
            'deskripsi'   => 'required'
        ]);

        $slug = Str::slug($request->input('name_group'));

        if ($request->file('image_url') == "") {
            $data =  CkanApi::group()->update([
                'id' => $id,
                'display_name' => $request->name_group,
                'description' => $request->deskripsi,
                'name' => $slug,
                'title' => $request->name_group
            ]);
        } else {
            $image = $request->file('image_url');

            $find =  CkanApi::group()->show($id);
            $data = $find['result'];

            if (Storage::exists('public/groups/' . array_reverse(explode('/', $data['image_display_url']))[0])) {
                Storage::delete('public/groups/' . array_reverse(explode('/', $data['image_display_url']))[0]);
            }

            $path = $image->storeAs('public/groups', $image->hashName());
            $public_path = Storage::url($path);

            $data = CkanApi::group()->update([
                'id' => $id,
                'description' => $request->deskripsi,
                'image_display_url' => $public_path,
                'name' => $slug,
                'image_url' => $public_path,
                'title' => $request->name_group
            ]);
        }
        if ($data) {
            //redirect dengan pesan sukses
            Alert::success('Berhasil', 'Data Berhasil Diubah!');
            return redirect()->route('group.index')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('group.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = CkanApi::group()->delete($id);
        if ($data) {
            //redirect dengan pesan sukses
            Alert::success('Berhasil', 'Data Berhasil Dihapus!');
            return redirect()->route('group.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } else {
            //redirect dengan pesan error
            return redirect()->route('group.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
