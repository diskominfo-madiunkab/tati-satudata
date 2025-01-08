<?php

namespace App\Http\Controllers;

use App\Models\Opd;
use App\Models\Role;
use Illuminate\Http\Request;
// use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::with('opd')->with('role')->get();
        return view('pages.contents.administrator.indexusers', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $role = Role::all();
        $data = User::with('opd')->with('role')->get();

        return view('pages.contents.administrator.createuser', (compact('data', 'opd', 'role')));
    }

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make('12345678'),
            'role_id' => $request->role_id,
            'opd_id' => $request->opd_id,
        ]);
        if ($request->role_id == '1') {
            $user->assignRole('administrator');
            activity()->performedOn($user)->log('membuat user (admin): ' . $request->username);
        } elseif ($request->role_id == '2') {
            $user->assignRole('walidata');
            activity()->performedOn($user)->log('membuat user (walidata): ' . $request->username);
        } elseif ($request->role_id == '3') {
            $user->assignRole('produsen');
            activity()->performedOn($user)->log('membuat user (produsen): ' . $request->username);
        } elseif ($request->role_id == '4') {
            $user->assignRole('pembina');
            activity()->performedOn($user)->log('membuat user (pembina): ' . $request->username);
        } elseif ($request->role_id == '5') {
            $user->assignRole('walidatapendukung');
            activity()->performedOn($user)->log('membuat user (walidatapendukung): ' . $request->username);
        }
        Alert::success('Berhasil', 'Berhasil Menambahkan Data user!');
        return redirect('/user');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $byid = User::findOrFail($id);
        // $opd = Opd::all();
        $opdsQuery = Opd::select('id', 'nama_opd')
            ->whereNotIn('nama_opd', ['Adminstrator', 'Administrator', 'TATI']);
        $opd = $opdsQuery->get();
        $roles = Role::all();

        return view('pages.contents.administrator.edituser', compact('byid', 'opd', 'roles'));
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
        $user = User::findOrFail($id);
        $role = Role::findOrFail($request->role_id);

        // Hapus role lama jika berbeda dengan role baru
        if ($user->role_id != $request->role_id) {
            $user->roles()->detach(); // Hapus semua role terkait user
            $user->assignRole($role->name); // Tetapkan role baru
        }

        $user->update([
            'name' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'role_id' => $request->role_id,
            'opd_id' => $request->opd_id,
        ]);
        if ($request->role_id == '1') {
            $user->assignRole('administrator');
            activity()->performedOn($user)->log('mengubah user (admin): ' . $request->username);
        } elseif ($request->role_id == '2') {
            $user->assignRole('walidata');
            activity()->performedOn($user)->log('mengubah user (walidata): ' . $request->username);
        } elseif ($request->role_id == '3') {
            $user->assignRole('produsen');
            activity()->performedOn($user)->log('mengubah user (produsen): ' . $request->username);
        } elseif ($request->role_id == '4') {
            $user->assignRole('pembina');
            activity()->performedOn($user)->log('mengubah user (pembina): ' . $request->username);
        } elseif ($request->role_id == '5') {
            $user->assignRole('walidatapendukung');
            activity()->performedOn($user)->log('mengubah user (walidatapendukung): ' . $request->username);
        }

        activity()->log('Update User');
        Alert::success('Berhasil', 'Berhasil Mengupdate Data user!');
        return redirect('/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        activity()->log('Mengapus user: ' . $user->username);
        $user->delete();
        Alert::success('Berhasil', 'Berhasil Menghapus Data user!');

        return redirect('/user');
    }

    public function changePassword(Request $request, $userId)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:5|confirmed',
            'password_confirmation' => 'required|min:5'
        ]);

        $user = User::findOrFail($userId);

        if ($user->id == auth()->id()) {
            return redirect()->back()->with([
                Alert::error('Gagal!', 'Anda tidak dapat merubah password melalui fitur ini')
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()->with([
                Alert::error('Gagal!', join("\n", $validator->getMessageBag()->all()))
            ]);
        }

        $user->update([
            'password' => app('hash')->make($request->get('password'))
        ]);

        return redirect()->back()->with([
            Alert::success('Berhasil', 'Password berhasil diubah')
        ]);
    }
}
